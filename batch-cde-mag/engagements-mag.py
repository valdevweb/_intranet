# from datetime import datetime as dt
import pandas as pd
import time
import datetime
import MySQLdb
import numpy as np
import os


# pour monitorer la durée du script
start = time.time()

# var pour filtrer un an de donnée
fiveYears = datetime.datetime.now() - datetime.timedelta(days=365)
fiveYears = fiveYears.strftime('%Y-%m-%d')
today = datetime.date.today().strftime('%Y-%m-%d')
# date heure insert en db
todaySql = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')

# connexion à la db => recup url et définition dev ou prod
dir_path = os.path.dirname(os.path.realpath(__file__))


if("_" in dir_path):
    version = "_"
else:
    version = ""


mydb = MySQLdb.connect(host='localhost',
                       user='sql',
                       passwd='User19092017+',
                       db=version+'qlik')
cursor = mydb.cursor()

# fn qui permet secifier le type d'une colonne
# permet de résoudre de pb de type multiple (erreur retournée : dtypewarning columns have mixed types)
# Pandas is really nice because instead of stopping altogether, it guesses which dtype a column has. However, by default, Pandas has the low_memory=True argument.
# This means that the CSV file gets split up in multiple chunks and the guess is done for every chunk, resulting in a column with multiple dtypes
# https://www.roelpeters.be/solved-dtypewarning-columns-have-mixed-types-specify-dtype-option-on-import-or-set-low-memory-in-pandas/


def convert_dtype(x):
    if not x:
        return ''
    try:
        return str(x)
    except:
        return ''


file = "D:\\btlec\\dumps\\gessica\\SBBCFDEN_58.csv"

# fichier trop gros, on utilise chunk pour découper le fichier en plusieurs dataframe que l'on concatene ensuite
chunk = pd.read_csv(file, chunksize=1000000, converters={
                    'SBBCFDEN.DEN-IDE': convert_dtype}, parse_dates=['SBBCFDEN.MAJ-DATE'], keep_date_col=True,)
df = pd.concat(chunk)

# renam plante qd on travaille sur un gros fichier
# df = df.rename(columns={
#     'SBBCFDEN.DEN-IDE': 'btlec',
#     'SBBCFDEN.DEN-TYP': 'type_engagement',
#     'SBBCFDEN.DEN-EPCNUM': 'id_engagement',
#     'SBBCFDEN.DEN-FOU': 'cnuf',
#     'SBBCFDEN.DEN-ARTNUM': 'article',
#     'SBBCFDEN.DEN-QTE': 'qte_cde',
#     'SBBCFDEN.DEN-QTEREC': 'qte_recue',
#     'SBBCFDEN.DEN-COL': 'colis_cde',
#     'SBBCFDEN.DEN-COLREC': 'colis_recu',
#     'SBBCFDEN.MAJ-DATE': 'SBBCFDEN.MAJ-DATE',
#     'SBBCFDEN.DEN-QTEINI': 'qte_init',
# })


# filtrage des données :
isBtlec = df['SBBCFDEN.DEN-IDE'] == "0987"
df = df[isBtlec]

# on  duplique la colonne date parce que je ne sais pas quel nom elle a une fois qu'elle est utilisé en index !..
df['date_update'] = df['SBBCFDEN.MAJ-DATE']
# on index par la colonne date pour pouvoir filtrer par dates
df = df.set_index(['SBBCFDEN.MAJ-DATE'])
df = df.sort_index().loc[fiveYears:today]

# on selectionne les colonnes utiles
df = df[['SBBCFDEN.DEN-IDE',  'SBBCFDEN.DEN-EPCNUM', 'SBBCFDEN.DEN-ARTNUM', 'SBBCFDEN.DEN-QTE',
         'SBBCFDEN.DEN-QTEREC', 'SBBCFDEN.DEN-COL', 'SBBCFDEN.DEN-COLREC', 'date_update', 'SBBCFDEN.DEN-QTEINI']]
# print(df)

# on efface la table engagement
cursor.execute("DELETE FROM engagements")
mydb.commit()

# parcours pour insertion en db
for index, row in df.iterrows():
    if row['SBBCFDEN.DEN-IDE'] == "SBBCFDEN.DEN-IDE":
        row += 1
    else:
        if row['SBBCFDEN.DEN-QTE']!=0:
            cursor.execute('INSERT INTO engagements(id_engagement, article_gessica, qte_cde, qte_recue, colis_cde,  colis_recu, qte_init, date_import) VALUES(%s, "%s",%s, %s, %s, %s, %s,%s )',
                       (row['SBBCFDEN.DEN-EPCNUM'], row['SBBCFDEN.DEN-ARTNUM'], row['SBBCFDEN.DEN-QTE'], row['SBBCFDEN.DEN-QTEREC'], row['SBBCFDEN.DEN-COL'], row['SBBCFDEN.DEN-COLREC'], row['SBBCFDEN.DEN-QTEINI'], todaySql))
            mydb.commit()

end = time.time()


# print("end import in : ", (end-start), "sec")
