import pandas as pd
import time
import datetime
import MySQLdb
import numpy as np


fiveYears=datetime.datetime.now() - datetime.timedelta(days=5*365)
fiveYears=fiveYears.strftime('%Y-%m-%d')

from datetime import datetime
today=datetime.today().strftime('%Y-%m-%d')
todaySql=datetime.today().strftime('%Y-%m-%d %H:%M:%S')
print(todaySql)
start = time.time()
# SCEBFMET.csv
file="D:\\documents\\p_pilotage\\codelec2.csv"

print("heelo")
df = pd.read_csv(file, low_memory=False, )
df['GT'] = df['GT'].replace(np.nan, 0)
df['NBRE PALETTES CODELEC'] = df['NBRE PALETTES CODELEC'].replace(np.nan, 0)
df['GFAM'] = df['GFAM'].replace(np.nan, "")
df['FAM'] = df['FAM'].replace(np.nan,"")
df['SOUS FAM'] = df['SOUS FAM'].replace(np.nan, "")
# df = df['NBRE PALETTES CODELEC'].replace(np.nan, 0)
df = df.astype({"GT": int, "CODELEC": int, "GFAM": str, "FAM":str, "SOUS FAM" : str, "NBRE PALETTES CODELEC": int })
print(df)
print (df.dtypes)

mydb = MySQLdb.connect(host='localhost',
	user='sql',
	passwd='User19092017+',
	db='_pilotage')
cursor = mydb.cursor()


for index, row in df.iterrows():
	if row['GT']=="GT":
		row += 1
	else:
		cursor.execute('INSERT INTO codelecs (gt, codelec, main_famille, famille, sous_famille, colis_palette) VALUES("%s", "%s",%s, %s, %s, "%s")',  (row['GT'], row['CODELEC'], row['GFAM'], row['FAM'], row['SOUS FAM'], row['NBRE PALETTES CODELEC']))
		mydb.commit()




































# df=df[["SCEBFMET.ART-COD", "SCEBFMET.DOS-COD", "SCEBFMET.MET-DAT", "SCEBFMET.MET-PANFAC", "SCEBFMET.MET-PRXFNP", "SCEBFMET.MET-MARG", "SCEBFMET.MET-PRXCONS", "SCEBFMET.FOU-COD" ]]

# df['date_valo']=df['SCEBFMET.MET-DAT']


# df = df.set_index(['SCEBFMET.MET-DAT'])

# df =df.sort_index().loc[fiveYears:today]
# df['art_dos']=df['SCEBFMET.DOS-COD'].apply(str)+df['SCEBFMET.ART-COD'].apply(str)

# df=df.sort_values('SCEBFMET.MET-DAT').groupby('art_dos').tail(1)

# df['art_dos']=df['art_dos'].astype(float)





# print(df);
# end = time.time()
# print("Read csv without chunks: ",(end-start),"sec")

# print (df.dtypes)
# df = df.replace(np.nan, 0.00)
# mydb = MySQLdb.connect(host='localhost',
# 	user='sql',
# 	passwd='User19092017+',
# 	db='_qlik')
# cursor = mydb.cursor()

# for index, row in df.iterrows():
# 	if row['SCEBFMET.DOS-COD']=="SCEBFMET.DOS-COD":
# 		row += 1
# 	else:
# 		panf=float("{:.2f}".format(row['SCEBFMET.MET-PANFAC']))
# 		pfnp=float("{:.2f}".format(row['SCEBFMET.MET-PRXFNP']))
# 		marge=float("{:.2f}".format(row['SCEBFMET.MET-MARG']))
# 		pvc=float("{:.2f}".format(row['SCEBFMET.MET-PRXCONS']))


# 		dateValo=row['date_valo'].strftime('%Y-%m-%d')
# 		cursor.execute('INSERT INTO tarifs(id, artdos, article, dossier, date_valo, panf, pfnp, marge, pvc, cnuf, date_insert) VALUES("%s", "%s","%s", "%s", %s, "%s", "%s", "%s", "%s", "%s", %s )',  (row['art_dos'], row['art_dos'], row['SCEBFMET.ART-COD'], row['SCEBFMET.DOS-COD'], dateValo, panf, pfnp, marge, pvc, row['SCEBFMET.FOU-COD'], todaySql))
# 		mydb.commit()


# end = time.time()


# print("end import in : ",(end-start),"sec")
