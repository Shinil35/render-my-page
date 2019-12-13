import requests
import uuid

# Auth headers
headers = {
    'Authorization': 'Basic emVuaGFjazozdjJZbmJBeXdmV1phYjNh',
}

base_url = 'http://18.195.29.111'

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

    s.post('%s/registration.php' % base_url, headers=headers, data=reg_data)
    user_count += 1

    for i in range(0, 2):
        new_page_text = s.get('%s/new_page.php' % base_url, headers=headers).text
        csrf_token = new_page_text.split('"csrf_token" value="')[1].split('"')[0]
        eploit = '''Test content'''

        eploit_id = str(uuid.uuid4())
        page_data = {
            'name': 'eploit_' + eploit_id,
            'content': eploit,
            'csrf_token': csrf_token,
        }

        response = s.post('%s/new_page.php' % base_url, headers=headers, data=page_data)
        print("Response: " + response.text.split('<div class="panel-heading">')[1].split('</div>')[0].strip())
        page_count += 1