SELECT
    users.*
FROM users
JOIN objects
    ON (objects.id = users.object_id)
