import sqlalchemy
import pandas as pd

df = pd.read_csv('data/PFALL15_new.csv')
engine = sqlalchemy.create_engine('sqlite:///hb.db')

types = {'carrier': sqlalchemy.types.Integer, 'hcpcs': sqlalchemy.types.String, 'locality': sqlalchemy.types.Integer, 'facFee': sqlalchemy.types.Float, 'nonFacFee': sqlalchemy.types.Float}

df.to_sql("hb2", engine, if_exists='replace', dtype=types)