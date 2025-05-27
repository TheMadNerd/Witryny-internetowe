INSERT INTO Subjects (name) VALUES
                                ('Matematyka'),
                                ('Fizyka'),
                                ('Język angielski');

INSERT INTO Users (name, email, password_hash, role_id) VALUES
                                                            ('Jan Student', 'student@example.com', '$2y$10$teststudenthash', 1),
                                                            ('Anna Tutor', 'tutor@example.com', '$2y$10$testtutorhash', 2),
                                                            ('Admin Admin', 'admin@example.com', '$2y$10$testadminhash', 3);

INSERT INTO TutorProfile (tutor_id, bio, hourly_rate) VALUES
    (2, 'Doświadczona korepetytorka matematyki i angielskiego.', 80.00);

INSERT INTO TutorSubjects (tutor_id, subject_id) VALUES
                                                     (2, 1),
                                                     (2, 3);

INSERT INTO Bookings (student_id, tutor_id, subject_id, booking_time, status) VALUES
    (1, 2, 1, '2025-06-01 17:00:00', 'pending');
