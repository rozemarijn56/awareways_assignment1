CREATE TABLE IF NOT EXISTS user_activity_events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid TEXT NOT NULL UNIQUE CHECK(length(uuid) >= 32),
    raw_id INTEGER NOT NULL,
    user_id TEXT NOT NULL,
    type_activity TEXT NOT NULL CHECK(   
        type_activity IN (
            'training_started',
            'progress_made',
            'points_scored',
            'training_completed'
        )
    ),
    occurred_at TEXT NOT NULL,
    training_id TEXT NULL,
    progress REAL NULL CHECK (
        progress IS NULL OR (progress >= 0 AND progress <= 100)
    ),
    points INTEGER NULL CHECK (
        points IS NULL OR points >= 0
    ),
    metadata TEXT NULL CHECK (
        metadata IS NULL OR json_valid(metadata)
    ),
    FOREIGN KEY (raw_id) REFERENCES events_raw(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_activity_user_time
ON user_activity_events(user_id, occurred_at);

CREATE INDEX IF NOT EXISTS idx_activity_type_acitvity
ON user_activity_events(user_id, type_activity);

CREATE INDEX IF NOT EXISTS idx_activity_training_id
ON user_activity_events(user_id, training_id);