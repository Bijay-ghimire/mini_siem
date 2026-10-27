import requests
response=requests.get("http://localhost/mini_siem/api.php")
data=response.json()

print(len(data))
print(data[0])
print(data)
