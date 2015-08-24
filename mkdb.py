import pandas as pd
import sqlalchemy
import csv

df = pd.read_csv('data/PFALL15.txt', skipfooter=4)
df.drop(['2015', '9', ' ' ,'  ' ,'A' ,'0000000.00.3' ,'0' ,'0000000.00.2' ,'0.1' ,'0000000.00', '0000000.00.1'], \
        inplace=True, axis=1)
cols = ['carrier', 'locality', 'hcpcs', 'nonFacFee', 'facFee']

df.to_csv('data/PFALL15_new.csv', index=False, header=cols)
df = pd.read_csv('data/PFALL15_new.csv')

carriers = []
with open('data/PFALL15.txt', 'rb') as csvfile:
    rdr = csv.reader(csvfile, delimiter=' ', quotechar='|')
    for i, row in enumerate(rdr):
        if i == 789660: # ignore footer
            break
        r = ', '.join(row)[8:18]
        r = r[:5] + r[8:10]
        carriers.append(r)

# off by one hack
carriers = carriers[1:]
del df['carrier']
df['carrier'] = carriers

lookup = []
with open('data/PF15PA.csv', 'rb') as csvfile:
    for row in csvfile:
        lookup.append(row.strip().split(','))
        
lookup = {carrier[0]: carrier[1:] for carrier in lookup}
df['state'] = df['carrier'].map(lambda x: lookup[x][0])
df['location'] = df['carrier'].map(lambda x: lookup[x][1])

localities = []
with open('data/PFALL15.txt', 'rb') as csvfile:
    rdr = csv.reader(csvfile, delimiter=' ', quotechar='|')
    for i, row in enumerate(rdr):
        if i == 789660:
            break
        r = ', '.join(row)[16:18]
        localities.append(r)

localities = localities[1:]
df['locality'] = localities

engine = sqlalchemy.create_engine('sqlite:///healthbase.db')

types = {'carrier': sqlalchemy.types.String, 'hcpcs': sqlalchemy.types.String, \
         'locality': sqlalchemy.types.String, 'facFee': sqlalchemy.types.Float, \
         'nonFacFee': sqlalchemy.types.Float, 'state': sqlalchemy.types.String, \
         'location': sqlalchemy.types.String}

df.to_sql("h", engine, if_exists='replace', dtype=types)