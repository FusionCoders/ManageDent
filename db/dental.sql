PRAGMA foreign_keys = ON;
.headers on
.mode columns

DROP TABLE IF EXISTS Report;
DROP TABLE IF EXISTS Medical_Procedure;
DROP TABLE IF EXISTS Dentist_Specialty;
DROP TABLE IF EXISTS Machine_Appointment;
DROP TABLE IF EXISTS Appointment;
DROP TABLE IF EXISTS Specialty;
DROP TABLE IF EXISTS Patient;
DROP TABLE IF EXISTS Assistant;
DROP TABLE IF EXISTS Machine;
DROP TABLE IF EXISTS Brand;
DROP TABLE IF EXISTS Dentist;
DROP TABLE IF EXISTS Schedule;

CREATE TABLE Dentist (
    id INTEGER PRIMARY KEY,
    person_name TEXT NOT NULL CONSTRAINT check_empty_or_spaces CHECK (person_name <> '' AND TRIM(person_name) <> ''),
    tax_id INTEGER NOT NULL UNIQUE CONSTRAINT check_tax_id CHECK (LENGTH(tax_id) = 9),
    birth_date TEXT NOT NULL,
    phone_number INTEGER NOT NULL UNIQUE CONSTRAINT check_phone_number CHECK (LENGTH(phone_number) = 9),
    email TEXT NOT NULL UNIQUE CONSTRAINT check_email CHECK (email LIKE '%@%.%'),
    salary FLOAT NOT NULL CONSTRAINT minimum_salary CHECK (salary >= 760),
    office INTEGER NOT NULL,
    dentist_password TEXT NOT NULL,
    photo TEXT NOT NULL UNIQUE,
    active_dentist INTEGER DEFAULT 1,
    schedule1_id INTEGER NOT NULL REFERENCES Schedule(id),
    schedule2_id INTEGER NOT NULL REFERENCES Schedule(id),
    schedule3_id INTEGER NOT NULL REFERENCES Schedule(id),
    schedule4_id INTEGER REFERENCES Schedule(id),
    schedule5_id INTEGER REFERENCES Schedule(id)
);

CREATE TRIGGER check_birth_date_dentist
BEFORE INSERT ON Dentist
FOR EACH ROW
WHEN NEW.birth_date > date('now')
BEGIN
   SELECT RAISE(ABORT, 'Date of birth must be less than the current date');
END;

CREATE TABLE Patient (
    id INTEGER PRIMARY KEY,
    person_name TEXT NOT NULL CONSTRAINT check_empty_or_spaces CHECK (person_name <> '' AND TRIM(person_name) <> ''),
    tax_id INTEGER NOT NULL UNIQUE CONSTRAINT check_tax_id CHECK (LENGTH(tax_id) = 9),
    birth_date TEXT NOT NULL,
    phone_number INTEGER NOT NULL UNIQUE CONSTRAINT check_phone_number CHECK (LENGTH(phone_number) = 9),
    email TEXT NOT NULL UNIQUE CONSTRAINT check_email CHECK (email LIKE '%@%.%')
);

CREATE TRIGGER check_birth_date_patient
BEFORE INSERT ON Patient
FOR EACH ROW
WHEN NEW.birth_date > date('now')
BEGIN
   SELECT RAISE(ABORT, 'Date of birth must be less than the current date');
END;

CREATE TABLE Assistant (
    id INTEGER PRIMARY KEY,
    person_name TEXT NOT NULL CONSTRAINT check_empty_or_spaces CHECK (person_name <> '' AND TRIM(person_name) <> ''),
    tax_id INTEGER NOT NULL UNIQUE CONSTRAINT check_tax_id CHECK (LENGTH(tax_id) = 9),
    birth_date TEXT NOT NULL,
    phone_number INTEGER NOT NULL UNIQUE CONSTRAINT check_phone_number CHECK (LENGTH(phone_number) = 9),
    email TEXT NOT NULL UNIQUE CONSTRAINT check_email CHECK (email LIKE '%@%.%'),
    salary FLOAT NOT NULL CONSTRAINT minimum_salary CHECK (salary >= 760),
    assistant_password TEXT NOT NULL,
    photo TEXT NOT NULL UNIQUE,
    active_assistant INTEGER DEFAULT 1,
    schedule1_id INTEGER NOT NULL REFERENCES Schedule(id),
    schedule2_id INTEGER NOT NULL REFERENCES Schedule(id),
    schedule3_id INTEGER NOT NULL REFERENCES Schedule(id),
    schedule4_id INTEGER REFERENCES Schedule(id),
    schedule5_id INTEGER REFERENCES Schedule(id)
);

CREATE TRIGGER check_birth_date_assistant
BEFORE INSERT ON Assistant
FOR EACH ROW
WHEN NEW.birth_date > date('now')
BEGIN
   SELECT RAISE(ABORT, 'Date of birth must be less than the current date');
END;

CREATE TABLE Appointment (
    id INTEGER PRIMARY KEY,
    date_appointment TEXT NOT NULL,
    dentist_id INTEGER NOT NULL REFERENCES Dentist(id),
    patient_id INTEGER NOT NULL REFERENCES Patient(id),
    assistant_id INTEGER NOT NULL REFERENCES Assistant(id),
    schedule_id INTEGER NOT NULL REFERENCES Schedule(id)
);

CREATE TABLE Specialty (
    id INTEGER PRIMARY KEY,
    specialty_name TEXT NOT NULL UNIQUE CONSTRAINT check_empty_or_spaces CHECK (specialty_name <> '' AND TRIM(specialty_name) <> ''),
    active_specialty INTEGER DEFAULT 1
);

CREATE TABLE Dentist_Specialty (
    specialty_id INTEGER REFERENCES Specialty(id),
    dentist_id INTEGER REFERENCES Dentist(id),
    PRIMARY KEY (specialty_id, dentist_id)
);

CREATE TABLE Medical_Procedure (
    id INTEGER PRIMARY KEY,
    medical_procedure_name TEXT NOT NULL UNIQUE CONSTRAINT check_empty_or_spaces CHECK (medical_procedure_name <> '' AND TRIM(medical_procedure_name) <> ''),
    specialty_id REFERENCES Specialty(id)
);

CREATE TABLE Report (
    report_id INTEGER PRIMARY KEY,
    observations TEXT,
    appointment_id INTEGER NOT NULL UNIQUE REFERENCES Appointment(id) ON DELETE CASCADE,
    medical_procedure_id INTEGER NOT NULL REFERENCES Medical_Procedure(id)
);

CREATE TABLE Brand (
    id INTEGER PRIMARY KEY,
    brand_name TEXT NOT NULL UNIQUE CONSTRAINT check_empty_or_spaces CHECK (brand_name <> '' AND TRIM(brand_name) <> '')
);

CREATE TABLE Machine (
    reference_number INTEGER PRIMARY KEY,
    machine_name TEXT NOT NULL CONSTRAINT check_name_empty_or_spaces CHECK (machine_name <> '' AND TRIM(machine_name) <> ''),
    model INTEGER NOT NULL CONSTRAINT check_model_empty_or_spaces CHECK (model <> '' AND TRIM(model) <> ''),
    photo TEXT NOT NULL UNIQUE,
    active_machine INTEGER DEFAULT 1,
    brand_id INTEGER REFERENCES Brand(id)
);

CREATE TABLE Machine_Appointment (
    appointment_id INTEGER REFERENCES Appointment(id) ON DELETE CASCADE,
    machine_id INTEGER REFERENCES Machine(reference_number),
    PRIMARY KEY (appointment_id, machine_id)
);

CREATE TABLE Schedule (
    id INTEGER PRIMARY KEY,
    week_day INTEGER NOT NULL CONSTRAINT check_week_day CHECK (week_day>=1 AND week_day<=5),
    start_time TEXT NOT NULL,
    end_time TEXT NOT NULL
);


-------------------------Schedule/Dentist
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (1, 1, '08:00', '18:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (2, 2, '09:00', '19:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (3, 3, '08:00', '18:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (4, 4, '08:00', '19:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (5, 5, '08:00', '18:00');
INSERT INTO Dentist (id, person_name, tax_id, birth_date, phone_number, email, salary, office, dentist_password, photo, active_dentist, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (1, 'Dra. Mariana Pais', 111111111, '2001-07-10', '919210210', 'marianapais@gmail.com', 2400, 2, '$2y$10$GUWQtBnvTLqGacKkmK596.ellEnXnY97QMUYzgK9F/BWqWcW8iQjG', '../img/Med1.png', 1, 1, 2, 3, 4, 5);

INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (6, 1, '09:00', '18:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (7, 2, '08:00', '18:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (8, 3, '09:00', '13:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (9, 4, '09:00', '16:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (10, 5, '12:00', '18:00');
INSERT INTO Dentist (id, person_name, tax_id, birth_date, phone_number, email, salary, office, dentist_password, photo, active_dentist, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (2, 'Dr. João Salgado', 111111112, '2001-07-10', '919211211', 'joaosalgado@gmail.com', 2300, 1, '$2y$10$6DQ4Uc0lYOUahRovfpCDE./PfJxyA0KEj4IymLzw32Unew5lsaAzm', '../img/Med2.png', 1, 6, 7, 8, 9, 10);

INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (11, 1, '08:30', '17:30');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (12, 2, '09:30', '12:30');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (13, 3, '12:00', '18:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (14, 4, '10:30', '20:30');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (15, 5, '08:30', '15:30');
INSERT INTO Dentist (id, person_name, tax_id, birth_date, phone_number, email, salary, office, dentist_password, photo, active_dentist, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (3, 'Dr. Diogo Bastos', 111111113, '2001-07-10', '919212212', 'diogobastos@gmail.com', 3500, 2, '$2y$10$XG5bLCSkQGBsFwGGSrhWru9OR9Zn9IzFzdYAr9nltKX16Va/JBNky', '../img/Med3.png', 1, 11, 12, 13, 14, 15);

INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (16, 1, '08:00', '17:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (17, 2, '10:00', '14:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (18, 3, '12:00', '18:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (19, 4, '09:00', '19:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (20, 5, '08:30', '16:30');
INSERT INTO Dentist (id, person_name, tax_id, birth_date, phone_number, email, salary, office, dentist_password, photo, active_dentist, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (4, 'Dra. Bárbara Teixeira', 111111114, '2001-07-10', '919213213', 'babampteixeira@gmail.com', 3500, 4, '$2y$10$sybJ9F1IkuqgUx2xCggESu4iGE7qjlFBlaR53zui/RGxKdDMUqDki', '../img/Med4.png', 1, 16, 17, 18, 19, 20);

INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (21, 1, '09:30', '17:30');
-- INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (22, 2, '10:30', '15:30');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (23, 3, '13:00', '19:00');
-- INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (24, 4, '09:30, '18:30);
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (25, 5, '09:00', '16:00');
INSERT INTO Dentist (id, person_name, tax_id, birth_date, phone_number, email, salary, office, dentist_password, photo, active_dentist, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (5, 'Dr. José Queirós', 111111115, '2001-07-10', '919214214', 'josequeiros@gmail.com', 3500, 1, '$2y$10$.CNCYCxz75YmPApuRGzqYudqGehMiIIpVgfOgc34F2PxaIAW8TJqG', '../img/Med5.png', 1, 21, 23, 25, NULL, NULL);

-- INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (26, 1, '08:00', '17:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (27, 2, '10:00', '14:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (28, 3, '12:00', '18:00');
-- INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (29, 4, '09:00', '19:00');
INSERT INTO Schedule (id, week_day, start_time, end_time) VALUES (30, 5, '08:30', '16:30');
INSERT INTO Dentist (id, person_name, tax_id, birth_date, phone_number, email, salary, office, dentist_password, photo, active_dentist, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (6, 'Dr. Carlos Videiras', 111111116, '2002-07-10', '919215215', 'carlosoliveira@gmail.com', 1500, 3, '$2y$10$.CNCYCxz75YmPApuRGzqYudqGehMiIIpVgfOgc34F2PxaIAW8TJqG', '../img/Med6.png', 0, 27, 28, 30, NULL, NULL);


-------------------------Assistant
INSERT INTO Assistant (id, person_name, tax_id, birth_date, phone_number, email, salary, assistant_password, photo, active_assistant, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (1, 'Ass. Rogério Alberto', 111111116, '2001-05-10', '919210250', 'rogerioalberto@gmail.com', 1400, '$2y$10$GUWQtBnvTLqGacKkmK596.ellEnXnY97QMUYzgK9F/BWqWcW8iQjG', '../img/Ass1.png', 1, 1, 2, 3, 4, 5);
INSERT INTO Assistant (id, person_name, tax_id, birth_date, phone_number, email, salary, assistant_password, photo, active_assistant, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (2, 'Ass. Rute Marlene', 111111117, '2000-06-10', '919611211', 'rutemarlene@gmail.com', 1300, '$2y$10$6DQ4Uc0lYOUahRovfpCDE./PfJxyA0KEj4IymLzw32Unew5lsaAzm', '../img/Ass2.png', 1, 1, 2, 3, 4, 5);
INSERT INTO Assistant (id, person_name, tax_id, birth_date, phone_number, email, salary, assistant_password, photo, active_assistant, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (3, 'Ass. Marta Leal', 111111118, '2001-05-10', '919210251', 'martaleal@gmail.com', 1400, '$2y$10$GUWQtBnvTLqGacKkmK596.ellEnXnY97QMUYzgK9F/BWqWcW8iQjG', '../img/Ass3.png', 1, 1, 2, 3, 4, 5);
INSERT INTO Assistant (id, person_name, tax_id, birth_date, phone_number, email, salary, assistant_password, photo, active_assistant, schedule1_id, schedule2_id, schedule3_id, schedule4_id, schedule5_id) VALUES  (4, 'Ass. Quim Parreira', 111111119, '2000-06-10', '919611212', 'quimparreira@gmail.com', 1300, '$2y$10$6DQ4Uc0lYOUahRovfpCDE./PfJxyA0KEj4IymLzw32Unew5lsaAzm', '../img/Ass4.png', 1, 1, 2, 3, 4, 5);


-------------------------Brand/Machines
INSERT INTO Brand (id, brand_name) VALUES (1,'Medtronic');
INSERT INTO Machine (reference_number, machine_name, model, photo, active_machine, brand_id) VALUES  (1,'Y-ray','XR200-10','../img/Maq1.png', 1, 1);
INSERT INTO Machine (reference_number, machine_name, model, photo, active_machine, brand_id) VALUES  (3,'Dental Air Compressor','Schulz MCSV 30/250', '../img/Maq3.png', 1,1);

INSERT INTO Brand (id, brand_name) VALUES (2, 'Dentsply Sirona');
INSERT INTO Machine (reference_number, machine_name, model, photo, active_machine, brand_id) VALUES  (6,'Autoclave','Tuttnauer EZ9', '../img/Maq6.png', 1, 2);
INSERT INTO Machine (reference_number, machine_name, model, photo, active_machine, brand_id) VALUES  (7,'Autoclave','Maxwell C300', '../img/Maq7.png', 0, 2);

INSERT INTO Brand (id, brand_name) VALUES (3, 'Nobel Biocare');
INSERT INTO Machine (reference_number, machine_name, model, photo, active_machine, brand_id) VALUES  (4,'Light curing unit','3M ESPE Elipar S10', '../img/Maq4.png', 1, 3);

INSERT INTO Brand (id, brand_name) VALUES (4, 'Henry Schein Dental');
INSERT INTO Machine (reference_number, machine_name, model, photo, active_machine, brand_id) VALUES  (5,'Ultrasound','Dentsply Cavitron', '../img/Maq5.png', 1, 4);

INSERT INTO Brand (id, brand_name) VALUES (5, 'Straumann');
INSERT INTO Machine (reference_number, machine_name, model, photo, active_machine, brand_id) VALUES  (2,'X-ray','Sirona Orthophos', '../img/Maq2.png', 1, 5);


-------------------------Specialty/Medical_Procedure
INSERT INTO Specialty (id, specialty_name, active_specialty) VALUES (1, 'Orthodontics', 1);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (1, 'Braces Installation', 1);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (2, 'Mouthguard Fabrication', 1);

INSERT INTO Specialty (id, specialty_name, active_specialty) VALUES (2, 'Endodontics', 1);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (3, 'Root Canal Treatment', 2);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (4, 'Endodontic Retreatment', 2);

INSERT INTO Specialty (id, specialty_name, active_specialty) VALUES (3, 'Periodontics', 1);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (5, 'Scaling and Root Planing', 3);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (6, 'Periodontal Surgery', 3);

INSERT INTO Specialty (id, specialty_name, active_specialty) VALUES (4, 'Implantology', 1);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (7, 'Dental Implant Placement', 4);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (8, 'Bone Grafting', 4);

INSERT INTO Specialty (id, specialty_name, active_specialty) VALUES (5, 'Pediatric Dentistry', 1);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (9, 'Dental Sealants', 5);
INSERT INTO Medical_Procedure (id, medical_procedure_name, specialty_id) VALUES (10, 'Pulpotomy', 5);

INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (1, 1);

INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (4, 2);
INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (5, 2);

INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (5, 3);

INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (2, 4);
INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (4, 4);

INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (1, 5);
INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (2, 5);
INSERT INTO Dentist_Specialty (specialty_id, dentist_id) VALUES (3, 5);


-------------------------Patient
INSERT INTO Patient (id, person_name, tax_id, birth_date, phone_number, email) 
VALUES 
 (1, 'Leonor Pereira', 222222221, '2001-07-10', '919310310', 'leonorp@gmail.com'),
 (2, 'Francisco Santos', 222222222, '2001-07-10', '919311311', 'kikosantos@gmail.com'),
 (3, 'Maria Paiva', 222222223, '2001-07-10', '919312312', 'mariapaiva@gmail.com'),
 (4, 'Clara Frias', 222222224, '2001-07-10', '919313313', 'clarafrias@gmail.com'),
 (5, 'António Marques', 222222225, '2001-07-10', '919314314', 'antoniomarques@gmail.com'),
 (6, 'Maria Silva', 123456789, '1990-05-15', '912345678', 'maria.silva@example.com'),
 (7, 'João Santos', 987654321, '1988-12-03', '911111111', 'joao.santos@example.com'),
 (8, 'Ana Rodrigues', 555555555, '1995-07-20', '919999999', 'ana.rodrigues@example.com'),
 (9, 'Pedro Almeida', 222222232, '1985-02-10', '918888888', 'pedro.almeida@example.com'),
 (10, 'Sofia Costa', 333333333, '1992-09-25', '915555555', 'sofia.costa@example.com'),
 (11, 'Gabriela Ferreira', 222222226, '1991-08-05', '918818888', 'gabriela.ferreira@example.com'),
 (12, 'Miguel Sousa', 333333334, '1992-10-15', '919999919', 'miguel.sousa@example.com'),
 (13, 'Sara Gomes', 444444445, '1993-07-25', '911181111', 'sara.gomes@example.com'),
 (14, 'Ricardo Ribeiro', 555555557, '1994-09-08', '912315678', 'ricardo.ribeiro@example.com'),
 (15, 'Ana Oliveira', 666666667, '1995-12-18', '915555525', 'ana.oliveira@example.com'),
 (16, 'Tiago Martins', 777777778, '1996-04-30', '916676666', 'tiago.martins@example.com'),
 (17, 'Mariana Santos', 888888889, '1997-11-12', '917773777', 'mariana.santos@example.com'),
 (18, 'Diogo Silva', 999999990, '1998-08-22', '913333333', 'diogo.silva@example.com'),
 (19, 'Inês Rodrigues', 101011011, '1999-05-02', '915444444', 'ines.rodrigues@example.com'),
 (20, 'Miguel Pereira', 444434444, '1998-06-18', '916666666', 'miguel.pereira@example.com'),
 (21, 'Marta Pereira', 222222233, '1991-06-25', '916666676', 'marta.pereira@example.com'),
 (22, 'Hugo Carvalho', 333333344, '1992-10-08', '917777777', 'hugo.carvalho@example.com'),
 (23, 'Sofia Ferreira', 444444455, '1993-12-21', '914888888', 'sofia.ferreira@example.com'),
 (24, 'Gonçalo Sousa', 555555566, '1994-08-03', '919699999', 'goncalo.sousa@example.com'),
 (25, 'Andreia Gomes', 666666677, '1995-01-16', '911118111', 'andreia.gomes@example.com'),
 (26, 'Rui Ribeiro', 777777788, '1996-05-29', '913345678', 'rui.ribeiro@example.com'),
 (27, 'Carla Oliveira', 888888899, '1997-09-11', '912333333', 'carla.oliveira@example.com'),
 (28, 'Mário Martins', 999999900, '1998-12-24', '914444444', 'mario.martins@example.com'),
 (29, 'Lara Santos', 101001012, '1999-07-07', '915556555', 'lara.santos@example.com'),
 (30, 'Luís Costa', 111111122, '1990-02-12', '915555455', 'luis.costa@example.com');

-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (1, '2023-06-06', 4, 1, 1, 11);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (2, '2023-06-06', 4, 2, 2, 12);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (3, '2023-06-05', 2, 2, 2, 1);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (4, '2023-06-06', 3, 2, 2, 13);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (5, '2023-06-09', 4, 3, 2, 41);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (6, '2023-06-09', 4, 4, 2, 42);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (7, '2023-06-12', 4, 4, 1, 4);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (8, '2023-06-12', 4, 1, 1, 7);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (9, '2023-06-13', 4, 5, 1, 11);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (10, '2023-06-13', 4, 1, 1, 13);
-- INSERT INTO Appointment (id, date_appointment, medico_id, patient_id, assistant_id, schedule_id) VALUES (11, '2023-06-14', 6, 3, 2, 21);


-------------------------Schedule/appointment/reports/machines
INSERT INTO Schedule (id, week_day, start_time, end_time)
VALUES 
 (101, 1, '08:00', '09:00'),
 (102, 1, '09:00', '10:00'),
 (103, 1, '10:00', '11:00'),
 (104, 1, '11:00', '12:00'),
 (105, 1, '12:00', '13:00'),
 (106, 1, '13:00', '14:00'),
 (107, 1, '14:00', '15:00'),
 (108, 1, '15:00', '16:00'),
 (109, 1, '16:00', '17:00'),
 (110, 1, '17:00', '18:00'),

 (111, 2, '08:00', '09:00'),
 (112, 2, '09:00', '10:00'),
 (113, 2, '10:00', '11:00'),
 (114, 2, '11:00', '12:00'),
 (115, 2, '12:00', '13:00'),
 (116, 2, '13:00', '14:00'),
 (117, 2, '14:00', '15:00'),
 (118, 2, '15:00', '16:00'),
 (119, 2, '16:00', '17:00'),
 (120, 2, '17:00', '18:00'),

 (121, 3, '08:00', '09:00'),
 (122, 3, '09:00', '10:00'),
 (123, 3, '10:00', '11:00'),
 (124, 3, '11:00', '12:00'),
 (125, 3, '12:00', '13:00'),
 (126, 3, '13:00', '14:00'),
 (127, 3, '14:00', '15:00'),
 (128, 3, '15:00', '16:00'),
 (129, 3, '16:00', '17:00'),
 (130, 3, '17:00', '18:00'),

 (131, 4, '08:00', '09:00'),
 (132, 4, '09:00', '10:00'),
 (133, 4, '10:00', '11:00'),
 (134, 4, '11:00', '12:00'),
 (135, 4, '12:00', '13:00'),
 (136, 4, '13:00', '14:00'),
 (137, 4, '14:00', '15:00'),
 (138, 4, '15:00', '16:00'),
 (139, 4, '16:00', '17:00'),
 (140, 4, '17:00', '18:00'),

 (141, 5, '08:00', '09:00'),
 (142, 5, '09:00', '10:00'),
 (143, 5, '10:00', '11:00'),
 (144, 5, '11:00', '12:00'),
 (145, 5, '12:00', '13:00'),
 (146, 5, '13:00', '14:00'),
 (147, 5, '14:00', '15:00'),
 (148, 5, '15:00', '16:00'),
 (149, 5, '16:00', '17:00'),
 (150, 5, '17:00', '18:00');


INSERT INTO Appointment (id, date_appointment, dentist_id, patient_id, assistant_id, schedule_id)
VALUES
  (1, '2023-06-12', 1, 1, 4, 101),
  (2, '2023-06-12', 1, 2, 2, 102),
  (3, '2023-06-12', 3, 3, 3, 103),
  (4, '2023-06-12', 4, 4, 4, 104),
  (5, '2023-06-12', 5, 5, 2, 105),
  (6, '2023-06-12', 4, 6, 3, 106),
  (7, '2023-06-12', 3, 7, 3, 107),
  (8, '2023-06-12', 2, 8, 2, 108),
  (9, '2023-06-12', 2, 9, 3, 109),
  (10, '2023-06-12', 1, 10, 1, 110),

  (11, '2023-06-13', 2, 11, 1, 111),
  (12, '2023-06-13', 3, 12, 4, 112),
  (13, '2023-06-13', 1, 13, 3, 113),
  (14, '2023-06-13', 4, 14, 2, 114),
  (15, '2023-06-13', 6, 15, 3, 115),
  (16, '2023-06-13', 6, 16, 4, 116),
  (17, '2023-06-13', 1, 17, 1, 117),
  (18, '2023-06-13', 3, 18, 2, 118),
  (19, '2023-06-13', 1, 19, 3, 119),
  (20, '2023-06-13', 2, 20, 1, 120),

  (21, '2023-06-14', 3, 21, 1, 121),
  (22, '2023-06-14', 2, 22, 2, 122),
  (23, '2023-06-14', 2, 23, 3, 123),
  (24, '2023-06-14', 1, 24, 4, 124),
  (25, '2023-06-14', 6, 25, 1, 125),
  (26, '2023-06-14', 4, 26, 2, 126),
  (27, '2023-06-14', 5, 27, 3, 127),
  (28, '2023-06-14', 4, 28, 4, 128),
  (29, '2023-06-14', 5, 29, 1, 129),
  (30, '2023-06-14', 6, 30, 2, 130),

  (31, '2023-06-15', 1, 1, 3, 131),
  (32, '2023-06-15', 2, 2, 4, 132),
  (33, '2023-06-15', 3, 3, 1, 133),
  (34, '2023-06-15', 4, 4, 2, 134),
  (35, '2023-06-15', 3, 5, 3, 135),
  (36, '2023-06-15', 2, 6, 4, 136),
  (37, '2023-06-15', 1, 7, 1, 137),
  (38, '2023-06-15', 2, 8, 2, 138),
  (39, '2023-06-15', 3, 9, 3, 139),
  (40, '2023-06-15', 4, 10, 4, 140),

  (41, '2023-06-16', 3, 11, 1, 141),
  (42, '2023-06-16', 4, 12, 2, 142),
  (43, '2023-06-16', 1, 13, 3, 143),
  (44, '2023-06-16', 5, 14, 4, 144),
  (45, '2023-06-16', 6, 15, 1, 145),
  (46, '2023-06-16', 4, 16, 2, 146),
  (47, '2023-06-16', 5, 17, 3, 147),
  (48, '2023-06-16', 6, 18, 4, 148),
  (49, '2023-06-16', 1, 19, 1, 149),
  (50, '2023-06-16', 2, 20, 2, 150);


INSERT INTO Report (report_id, observations, appointment_id, medical_procedure_id)
VALUES 
(1, 'The patient shows signs of cavities in several teeth.', 1, 3),
(2, 'A professional dental cleaning is required for the patient.', 2, 2),
(3, 'Wisdom tooth extraction is recommended for the patient.', 3, 1),
(4, 'Orthodontic braces are recommended for dental correction.', 4, 6),
(5, 'The patient needs a restoration on one of the front teeth.', 5, 4),
(6, 'A panoramic X-ray is recommended.', 6, 9),
(7, 'The patient needs prophylaxis to remove plaque.', 7, 5),
(8, 'Fluoride application is recommended to strengthen tooth enamel.', 8, 8),
(9, 'The patient should schedule a routine check-up in six months.', 9, 3),
(10, 'Tooth extraction is recommended for a tooth affected by infection.', 10, 7),
(11, 'Root canal treatment is necessary for one of the molars.', 11, 10),
(12, 'The patient shows signs of gingivitis and needs proper treatment.', 12, 1),
(13, 'Daily use of dental floss is recommended for oral hygiene.', 13, 2),
(14, 'A dental occlusion evaluation is important.', 14, 9),
(15, 'The patient should avoid foods and drinks high in sugar.', 15, 4),
(16, 'Teeth whitening is needed to improve the patient dental aesthetics.', 16, 7),
(17, 'Sealant application on the back teeth is recommended to prevent cavities.', 17, 5),
(18, 'Tartar removal is required for the patient.', 18, 2),
(19, 'Evaluation of possible impacted wisdom teeth is recommended.', 19, 1),
(20, 'The patient should use a soft-bristled toothbrush to avoid gum injuries.', 20, 3),
(21, 'The patient has a cavity in a front tooth and needs restoration.', 21, 6),
(22, 'Mouthwash use is recommended to complement oral hygiene.', 22, 4),
(23, 'Occlusion adjustment is needed to improve teeth alignment.', 23, 10),
(24, 'A night guard is recommended to prevent tooth damage.', 24, 9),
(25, 'The patient should schedule a consultation for dental implant evaluation.', 25, 8),
(26, 'Professional cleaning is needed to remove tooth stains.', 26, 3),
(27, 'Fluoride mouthwash use is recommended to strengthen teeth.', 27, 5),
(28, 'A radiographic exam is needed to evaluate root health.', 28, 1),
(29, 'Use of antiseptic toothpaste is recommended.', 29, 2),
(30, 'The patient should limit alcohol consumption for oral health.', 30, 7),
(31, 'The patient has tooth sensitivity; a specific toothpaste is recommended.', 31, 4),
(32, 'A panoramic X-ray is recommended for a comprehensive dental evaluation.', 32, 1),
(33, 'Dentures need adjustment for better fit and comfort.', 33, 9),
(34, 'Daily flossing is recommended for proper interdental cleaning.', 34, 3),
(35, 'Root canal treatment is needed on a back tooth.', 35, 6),
(36, 'Topical fluoride application is recommended to strengthen enamel.', 36, 5),
(37, 'The patient has gingivitis; deep cleaning is required.', 37, 3),
(38, 'Dentures need adjustment for better stability during chewing.', 38, 9),
(39, 'Wisdom tooth removal is recommended to prevent future issues.', 39, 7),
(40, 'The patient has excessive tooth wear and needs restoration.', 40, 6),
(41, 'A soft-bristled toothbrush is recommended to prevent gum damage.', 41, 2),
(42, 'Orthodontic evaluation is needed for malocclusion correction.', 42, 10),
(43, 'Fluoride varnish application is recommended to prevent cavities.', 43, 5),
(44, 'Teeth whitening is needed to improve smile aesthetics.', 44, 8),
(45, 'A tongue scraper is recommended for better oral hygiene.', 45, 2),
(46, 'A back tooth with a cavity needs restoration.', 46, 6),
(47, 'Fluoride gel toothpaste is recommended for stronger teeth.', 47, 5),
(48, 'Periodontal exam is needed to evaluate gum health.', 48, 1),
(49, 'Sensitive-teeth toothpaste is recommended to prevent sensitivity.', 49, 4),
(50, 'Dentures need adjustment for better fit and stability.', 50, 9);


INSERT INTO Machine_Appointment(appointment_id, machine_id) 
VALUES 
 (25, 7),
 (10, 2),
 (47, 4),
 (36, 6),
 (15, 3),
 (30, 1),
 (40, 5),
 (5, 7),
 (20, 2),
 (44, 4),
 (33, 6),
 (12, 3),
 (28, 1),
 (42, 5),
 (7, 7);

