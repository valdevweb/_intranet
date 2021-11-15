# import csv
# import mysql.connector

# connexion = mysql.connector.connect(

# host="localhost",
# user="sql",
# password="User19092017+",
# database="_qlik"
# )

# with open('test.csv') as csv_file:
#     csv_reader = csv.reader(csv_file, delimiter=',')
#     line_count = 0
# 	for row in csv_reader:
# 		if line_count == 0:
# 			# print(f'Column names are {", ".join(row)}')
# 			line_count += 1
# 		else:
# 			print(f'\t code article : {row[0]} dossier : {row[1]} PANF : {row[4]}.')

# 			request="INSERT INTO test (article, dossier) VALUES (%s, %s)"
# 			data=(row[0], row[1])
# 			curseur = connexion.cursor()
# 			curseur.execute(request, data)
# 			connexion.commit()
# 			line_count += 1
# 	print(f'Processed {line_count} lines.')




import csv
import MySQLdb

# https://webomnizz.com/read-and-import-file-with-pandas-to-mysql-database/

MYSQL_USER 		= 'sql'
MYSQL_PASSWORD 	= 'User19092017+'
MYSQL_HOST_IP 	= 'locahost'

MYSQL_DATABASE	= '_qlik'


from sqlalchemy import create_engine, select, MetaData, Table, and_


# engine = create_engine('mysql+mysqlconnector://'+MYSQL_USER+':'+MYSQL_PASSWORD+'@'+MYSQL_HOST_IP+'/'+MYSQL_DATABASE, echo=False)
engine = create_engine("localhost://sql:User19092017+@_qlik/schema")
metadata = MetaData(bind=None)
table = Table(
    'test',
    metadata,
    autoload=True,
    autoload_with=engine
)
stmt = select([
    table.columns.dossier,
    table.columns.article]
).where(and_(
    table.columns.column2 == 3
)
connection = engine.connect()
results = connection.execute(stmt).fetchall()
print(results)


# stmt = select(test).where(test.article == 3)
# print(stmt)

# mydb = MySQLdb.connect(host='localhost',
# 	user='sql',
# 	passwd='User19092017+',
# 	db='_qlik')
# cursor = mydb.cursor()
# line_count = 0
# with open('test.csv', 'r') as csvfile:
# 	csv_data = csv.reader(csvfile, delimiter=',')
# # csv_data = csv.reader(file('test.csv'))
# 	for row in csv_data :
# 		if line_count == 0:
# 			line_count += 1
# 		else:
# 			cursor.execute('INSERT INTO test(article, \
# 				  dossier)' \
# 				  'VALUES("%s", "%s")',
# 				  (row[0], row[1]))
# 		#close the connection to the database.
# 			mydb.commit()
			# line_count += 1

			# cursor.close()
			# print ("Done")