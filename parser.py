import json
import pymysql.cursors

connection = pymysql.connect(
    host='localhost',
    user='mysql',
    password='mysql',
    db='komisoft',
    charset='utf8mb4',
    cursorclass=pymysql.cursors.DictCursor
)

path = 'komisoft_test_data_with_engineers.json'
try:
    with connection.cursor() as cursor:
        with open(path, 'r') as f:
            data = json.loads(f.read())
            for val in data:
                try:
                  json_encoded = json.dumps(val["status_dates"])
                  sql = "INSERT INTO orders (id, status, priorty, status_dates) values (%s, %s, %s, %s)" 
                  cursor.execute(sql, args=(val["id"],val["status"],val["priorty"], json_encoded))
                except:
                  continue
                connection.commit()
finally:
    connection.close()
