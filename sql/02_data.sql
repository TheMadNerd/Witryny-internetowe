INSERT INTO Subjects (name) VALUES
    ('Matematyka'),
    ('Fizyka'),
    ('Język angielski');

INSERT INTO Users (name, email, password_hash, role_id) VALUES
    ('Jan Student', 'student@example.com', '$2y$10$.l9U2ucoqVa76XQwo.yOuuRVZbc3T5zFm5Cks7F02Z6oCReLlILzm', 1),
    ('Anna Tutor',  'tutor@example.com',   '$2y$10$o1NLOJeP1NXsjr0LZi1deuJFh5fTGrmwZSztufIIkf9wZEwXRC9Om', 2),
    ('Admin Admin', 'admin@example.com',   '$2y$10$a.LQ6kCgj8lZfDto5CZ9WeffrllQvGHsKKs1eQIUUQp5KjWAG9sA2', 3);

INSERT INTO TutorProfile (tutor_id, bio, hourly_rate) VALUES
    (2, 'Doświadczona korepetytorka matematyki i angielskiego.', 80.00);

INSERT INTO TutorSubjects (tutor_id, subject_id) VALUES
    (2, 1),
    (2, 3);

INSERT INTO Bookings (student_id, tutor_id, subject_id, booking_time, status) VALUES
    (1, 2, 1, '2025-06-01 17:00:00', 'pending');
