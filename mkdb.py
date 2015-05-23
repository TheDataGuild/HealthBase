# create db from clean PF 2015 file

import pandas as pd
df = pd.read_csv('data/PFALL15_new.csv')

from sqlalchemy import create_engine
engine = create_engine('sqlite:///hb.db')

df.to_sql("hb2", engine, if_exists='replace')