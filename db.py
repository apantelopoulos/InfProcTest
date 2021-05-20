import MySQLdb

db = MySQLdb.connect('18.189.59.220','root','','data');

cursor = connection.cursor()
cursor.execute("SELECT * FROM `data`;")
results = cursor.fetchall()
for r in results:
    print(r)
cursor.close()
connection.close()