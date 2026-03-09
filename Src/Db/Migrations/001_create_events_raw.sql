CREATE Table IF NOT EXISTS events_raw (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid TEXT NOT NULL UNIQUE CHECK(length(uuid) >= 32),
    received_at TEXT NOT NULL,
    source TEXT NOT NULL default 'Platform' CHECK(source IN('platform', 'api', 'import', 'test')),
    payload TEXT NOT NULL CHECK(json_valid(payload)),
    is_valid INTEGER NOT NULL DEFAULT 0 CHECK(is_valid IN (0,1)),
    error TEXT NULL
);

CREATE INDEX IF NOT EXISTS idx_events_raw_received_at 
ON events_raw(received_at);