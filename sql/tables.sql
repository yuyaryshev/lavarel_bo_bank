drop table users;
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE NOT NULL,
    birth_date DATE NULL,
	is_system boolean NOT NULL DEFAULT(false),
	balance NUMERIC(18,2) NOT NULL DEFAULT 0 CHECK (balance >= 0)
);

drop table transactions;
CREATE TABLE transactions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
	dt DATE,
    user_from_id UUID NOT NULL,
    user_to_id UUID NOT NULL,
    text VARCHAR(50) NOT NULL,
    amount NUMERIC(18,2) NOT NULL CHECK (amount > 0),
	operation_type char(1) not null,
	
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);