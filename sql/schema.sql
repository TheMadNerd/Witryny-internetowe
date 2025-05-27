CREATE TABLE Roles (
                       role_id SERIAL PRIMARY KEY,
                       role_name VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO Roles (role_name) VALUES
                                  ('student'),
                                  ('tutor'),
                                  ('admin');

CREATE TABLE Users (
                       user_id SERIAL PRIMARY KEY,
                       name VARCHAR(100) NOT NULL,
                       email VARCHAR(100) UNIQUE NOT NULL,
                       password_hash TEXT NOT NULL,
                       role_id INTEGER REFERENCES Roles(role_id) ON DELETE CASCADE
);

CREATE TABLE TutorProfile (
                              tutor_id INTEGER PRIMARY KEY REFERENCES Users(user_id) ON DELETE CASCADE,
                              bio TEXT,
                              hourly_rate NUMERIC(6,2)
);

CREATE TABLE Subjects (
                          subject_id SERIAL PRIMARY KEY,
                          name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE TutorSubjects (
                               tutor_id INTEGER REFERENCES Users(user_id) ON DELETE CASCADE,
                               subject_id INTEGER REFERENCES Subjects(subject_id) ON DELETE CASCADE,
                               PRIMARY KEY (tutor_id, subject_id)
);

CREATE TABLE Bookings (
                          booking_id SERIAL PRIMARY KEY,
                          student_id INTEGER REFERENCES Users(user_id) ON DELETE CASCADE,
                          tutor_id INTEGER REFERENCES Users(user_id) ON DELETE CASCADE,
                          subject_id INTEGER REFERENCES Subjects(subject_id) ON DELETE SET NULL,
                          booking_time TIMESTAMP NOT NULL,
                          status VARCHAR(20) DEFAULT 'pending'
);

CREATE VIEW BookingDetails AS
SELECT
    b.booking_id,
    s.name AS student_name,
    t.name AS tutor_name,
    subj.name AS subject,
    b.booking_time,
    b.status
FROM Bookings b
         JOIN Users s ON b.student_id = s.user_id
         JOIN Users t ON b.tutor_id = t.user_id
         LEFT JOIN Subjects subj ON b.subject_id = subj.subject_id;

CREATE OR REPLACE FUNCTION handle_user_delete()
RETURNS TRIGGER AS $$
BEGIN
DELETE FROM TutorProfile WHERE tutor_id = OLD.user_id;
DELETE FROM Bookings WHERE student_id = OLD.user_id OR tutor_id = OLD.user_id;
RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_after_user_delete
    AFTER DELETE ON Users
    FOR EACH ROW
    EXECUTE FUNCTION handle_user_delete();
