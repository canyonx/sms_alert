CREATE TABLE messenger_messages (
    id BIGINT GENERATED BY DEFAULT AS IDENTITY NOT NULL, 
    body TEXT NOT NULL, 
    headers TEXT NOT NULL, 
    queue_name VARCHAR(190) NOT NULL, 
    created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
    PRIMARY KEY(id)
)