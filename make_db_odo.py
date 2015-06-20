from odo import odo
odo('data/PFALL15_new.csv', 'sqlite:///healthbase.db::h', dshape=ds)