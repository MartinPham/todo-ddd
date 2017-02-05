DROP TABLE IF EXISTS tasks;
CREATE TABLE tasks (id INTEGER NOT NULL, name VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id));
CREATE INDEX status ON tasks (status);
INSERT INTO tasks (name, status) VALUES ('Buying salt', 'remaining');
INSERT INTO tasks (name, status) VALUES ('Buying milk', 'remaining');
INSERT INTO tasks (name, status) VALUES ('Go to supermarket', 'completed');