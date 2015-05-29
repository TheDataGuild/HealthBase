class Record(db.Model):

    __hb__ = "datatable"

    rec_id = db.Column(db.Integer, primary_key=True)
    carrier = db.Column(db.Integer, nullable=False)
    hcpcs = db.Column(db.String, nullable=False)
    locality = db.Column(db.Integer, nullable=False)
    fac_fee = db.Column(db.Float, nullable=False)
    non_fac_fee = db.Column(db.Float, nullable=False)

    def __init__(self, rec_id, carrier, hcpcs, locality, fac_fee, non_fac_fee)
        self.carrier = carrier
        self.hcpcs = hcpcs
        self.locality = locality
        self.fac_fee = fac_fee
        self.non_fac_fee = non_fac_fee

    def __repr__(self):
        return '<id {}>'.format(self.rec_id)