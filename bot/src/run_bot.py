import requests
import json
import base64
import time
import redis

from selenium import webdriver
from selenium.webdriver.chrome.options import Options  

# ---- START CONFIG ----
backdoor_pwd = '3074bc8c-0a1f-48ae-aedc-54fe9fdd8a32'
base_url = 'http://web'

page_list_cache_time = 10 # Seconds between page_list calls
# ---- END CONFIG ----

redis_db = redis.Redis(host='redis', port=6379, db=0)
error_image = base64.b64encode(open("error_image.png", "rb").read()).decode('utf-8')

def update_page_list_cache():
    # Check if we have to update page_list cache
    page_list_cache_timestamp = redis_db.get('page_list_time')

    if page_list_cache_timestamp is None or int(time.time()) > int(page_list_cache_timestamp) + page_list_cache_time:
        # Avoid (almost certain) page_list update collision
        redis_db.set('page_list_time', int(time.time()))

        # Get updated page_list
        pages = None
        try:
            res = requests.get('%s/bot/page_list.php?backdoor=%s' % (base_url, backdoor_pwd))
            pages = json.loads(res.text)
        except:
            print("Error while getting page list, server not ready yet?")

        if pages is not None and len(pages) > 0:
            for page in pages:
                redis_db.hset('page_users', page['id'], page['user_id'])
                redis_db.sadd('pages_to_process', page['id'])
    
        # Set real updated timestamp
        redis_db.set('page_list_time', int(time.time()))

        print("Updated page_list cache")

def get_next_page():
    # Get a random non-processed page
    next_page = redis_db.spop('pages_to_process')

    while next_page is not None:
        if redis_db.sadd('processed_pages'):
            return next_page

    return None

def get_page_user(page_id):
    return redis_db.hget('page_users', page_id)

def process_page(page_id, user_id):
    print("Processing page %d from user %d" % (page_id, user_id))
    
    # Get a session for this user
    res = requests.get('%s/bot/login_as_bot.php?backdoor=%s&user_id=%d' % (base_url, backdoor_pwd, user_id))
    session = json.loads(res.text)['PHPSESSID']

    dummy_page = '%s/empty.php' % (base_url)
    page_url = '%s/page.php?id=%d' % (base_url, page_id)
    cookies = 'PHPSESSID=%s;' % (session)

    image = error_image

    # Browser setup
    chrome_options = Options()
    chrome_options.add_argument("--no-sandbox")
    chrome_options.add_argument("--headless")
    chrome_options.add_argument("--disable-gpu")
    chrome_options.add_argument("--disable-dev-shm-using")
    chrome_options.add_argument("--disable-software-rasterizer")

    driver = webdriver.Chrome(options=chrome_options)
    driver.set_page_load_timeout(2)

    # Safe handling
    try:
        # Inject cookies
        driver.get(dummy_page)
        driver.add_cookie({'name': 'PHPSESSID', 'value': session})

        # Visit page
        driver.get(page_url)

        # Accept alert if present
        try:
            alert = driver.switch_to.alert
            alert.accept()
        except:
            pass

        image = driver.get_screenshot_as_base64()
    except Exception as e:
        print("Exception while taking screenshot: ")
        print(e)
    finally:
        driver.close()

    # Upload image or error
    data = {'page_id': page_id, 'image': 'data:image/png;base64,%s' % image}
    res = requests.post('%s/bot/upload_image.php?backdoor=%s' % (base_url, backdoor_pwd), data=data)


while True:
    time.sleep(1)

    update_page_list_cache()
    
    page_id = get_next_page()

    if page_id is None:
        continue

    user_id = get_page_user(page_id)
    if user_id is None:
        print("Page with no user (?)")
        continue

    process_page(page_id, user_id)

    