import requests
import json

# 1. List pages to process `bot/page_list.php`

# For each page:
#   Get session from backend
#   Visit page & take screenshot


backdoor_pwd = '3074bc8c-0a1f-48ae-aedc-54fe9fdd8a32'
base_url = 'http://127.0.0.1:8080'

headers = {

}

# List pages to process
res = requests.get('%s/bot/page_list.php?backdoor=%s' % (base_url, backdoor_pwd), headers=headers)
pages = json.loads(res.text)

for page in pages:
    page_id = int(page['id'])
    user_id = int(page['user_id'])

    print("Processing page %d from user %d" % (page_id, user_id))
    
    # Get a session for this user
    res = requests.get('%s/bot/login_as_bot.php?backdoor=%s&user_id=%d' % (base_url, backdoor_pwd, user_id), headers=headers)
    session = json.loads(res.text)['PHPSESSID']

    print("Got session: %s" % session)

    # TODO: Visit page and take screenshot

    # Upload image
    data = {'page_id': page_id, 'image': 'test'}

    res = requests.post('%s/bot/upload_image.php?backdoor=%s' % (base_url, backdoor_pwd), headers=headers, data=data)
    confirm = json.loads(res.text)
    print(confirm)