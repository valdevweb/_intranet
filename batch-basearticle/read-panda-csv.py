import pandas as pd
import time
import datetime
import MySQLdb
import numpy as np


# SCEBFMET.csv
file="animal.csv"
df = pd.read_csv(file, low_memory=False, decimal=",")

# df['nb'] = pd.to_numeric(df['nb'],errors = 'coerce')

print(df)

print (df.dtypes)
