from flask import Flask, render_template, url_for, request, session, flash, g, redirect
import sqlite3

app = Flask(__name__)
app.config.from_object('config')

def connect_db():
    return sqlite3.connect(app.config['DATABASE'])

@app.route("/", methods=['GET', 'POST'])
def login():
	# if request.method == 'POST':
	# 	if request.form['username'] != app.config['USERNAME'] or request.form['PASSWORD']:
	# 		error = 'Invalid Credentials'
	# 	else:
	# 		session['logged_in'] = True
	# 		return redirect(url_for('table'))
	return render_template('index.html')

@app.route('/logout')
def logout():
	# session.pop('logged_in', None)
	# flash('You were logged out')
	return redirect(url_for('login'))

@app.route("/table")
@app.route("/")
def table():
    conn = connect_db()
    cur = conn.execute("SELECT carrier, locality, hcpcs, nonFacFee, facFee FROM hb2 LIMIT 100;")
    pf15 = [dict(carrier=row[0], locality=row[1], hcpcs=row[2], non_fac_fee=row[3], fac_fee=row[4]) for row in cur.fetchall()]
    conn.close()
    return render_template('table.html', data=pf15)