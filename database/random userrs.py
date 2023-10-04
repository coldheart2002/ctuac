import random
from datetime import datetime, timedelta

# Generate random names for 10,000 users
names = [f'User {i}' for i in range(1, 10001)]

# Generate random IDs for 10,000 users
ids = [f'ID{i}' for i in range(1, 10001)]

# Generate random phone numbers for 10,000 users
phone_numbers = [f'{random.randint(100, 999)}-{random.randint(100, 999)}-{random.randint(1000, 9999)}' for _ in range(10000)]

# Generate random gname names for 10,000 users
gname = [f'gname {i}' for i in range(1, 10001)]

# Generate random course names for 10,000 users
courses = [f'Course {i}' for i in range(1, 10001)]

# Generate random blood types for 10,000 users
blood_types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']
blood = [random.choice(blood_types) for _ in range(10000)]

# Generate random birthdates between 1970 and 2005 for 10,000 users
birth_dates = [datetime(1970, 1, 1) + timedelta(days=random.randint(1, 12000)) for _ in range(10000)]
birth_dates = [date.strftime('%Y-%m-%d') for date in birth_dates]

# Get the current date and time
current_datetime = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

# Construct SQL INSERT statement
sql_insert_template = "INSERT INTO userdata (name, id, contact, gname, course, blood, bday, datetime) VALUES ('{}', '{}', '{}', '{}', '{}', '{}', '{}', '{}');"

# Combine all data into SQL statements with the current date and time
sql_statements = [sql_insert_template.format(name, _id, contact, gname, course, bt, bday, current_datetime) for name, _id, contact, gname, course, bt, bday in zip(names, ids, phone_numbers, gname, courses, blood, birth_dates)]

# Print or execute the SQL statements as needed
for sql_statement in sql_statements:
    print(sql_statement)
