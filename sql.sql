select
    name,
    status,
    login, 
    password
from objects
join users 
    on (objects.id = users.object_id)
