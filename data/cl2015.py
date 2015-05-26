# clean version of locality-specific physician fee schedule payment amounts for services covered by the Medicare Physician Fee Schedule

import pandas as pd
df = pd.read_csv('PFALL15.txt')

df.drop(['2015', '9', ' ' ,'  ' ,'A' ,'0000000.00.3' ,'0' ,'0000000.00.2' ,'0.1' ,'0000000.00', '0000000.00.1'], inplace=True, axis=1)

df = df[pd.notnull(df['G0101'])]

cols = ['carrier', 'locality', 'hcpcs', 'nonFacFee', 'facFee']
df.to_csv('PFALL15_new.csv', index=False, header=cols)

pd.read_csv('PFALL15_new.csv')