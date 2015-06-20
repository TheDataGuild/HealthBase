from flask import Flask, render_template, jsonify, make_response
import sqlite3
import json

app = Flask(__name__)
app.config.from_object('config')

def access_db():
    return sqlite3.connect(app.config['DATABASE'])

# app

@app.route("/")
def login():
    return render_template('index.html')

@app.route("/table")
def table():
    conn = access_db()
    cur = conn.execute("SELECT carrier, locality, hcpcs, nonFacFee, facFee \
                        FROM h LIMIT 1000;")
    pf15 = [dict(carrier=row[0], locality=row[1], hcpcs=row[2], \
            non_fac_fee=row[3], fac_fee=row[4]) for row in cur.fetchall()]
    conn.close()
    return render_template('table.html', data=pf15)

# api

@app.route('/gethcpcs', methods=['GET'])
def get_hcpcs():
    return get_data('hcpcs')

# sql injections = :<
def get_data(col):
    conn = access_db()
    q = "SELECT {} FROM h LIMIT 100;".format(col)
    cur = conn.execute(q)
    return json.dumps([dict(hcpcs=row[0]) for row in cur.fetchall()])

@app.errorhandler(404)
def not_found(error):
    return make_response(jsonify({'error': 'not found'}), 404)