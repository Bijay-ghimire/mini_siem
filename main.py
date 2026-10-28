import requests
import os
from datetime import datetime, timedelta
headers = {"X-API-Key": os.getenv("API_KEY")}
response = requests.get("http://localhost/mini_siem/api.php", headers=headers)
attempts = response.json()
BRUTE_FORCE_LIMIT = 5
SPRAY_LIMIT = 3
TIME_WINDOW = 10
cutoff = datetime.now() - timedelta(minutes=TIME_WINDOW)


recent_attempts = []
for attempt in attempts:
    attempt_time = datetime.strptime(attempt['time_of_attempt'], "%Y-%m-%d %H:%M:%S")
    if attempt_time >= cutoff:
        recent_attempts.append(attempt)


count = {}
for attempt in recent_attempts:
    ip = attempt['ip']
    success = attempt['success']
    if success == 1:
        continue
    if ip in count:
        count[ip] += 1
    else:
        count[ip] = 1

print(count)
for ip, total in count.items():
    if total >= BRUTE_FORCE_LIMIT:
        print("ALERT brute force:", ip, total, "failures")


sets_by_ip = {}
for attempt in recent_attempts:
    ip = attempt["ip"]
    username = attempt["username"]
    if attempt["success"] == 1:
        continue

    if ip not in sets_by_ip:
        sets_by_ip[ip] = set()

    sets_by_ip[ip].add(username)

for ip, usernames in sets_by_ip.items():
    if len(usernames) >= SPRAY_LIMIT:
        print("ALERT spray and pray:", ip, len(usernames), "unique users")
        