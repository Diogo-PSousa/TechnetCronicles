DROP SCHEMA IF EXISTS lbaw2361 CASCADE;

CREATE SCHEMA lbaw2361;

SET search_path TO lbaw2361;

-----------------------------------------
-- Types
-----------------------------------------

CREATE TYPE VoteTypes AS ENUM ('Upvote', 'Downvote');

-----------------------------------------
-- Tables
-----------------------------------------

-- Clean up existing tables if they exist
DROP TABLE IF EXISTS CommentVote CASCADE;
DROP TABLE IF EXISTS ArticleVote CASCADE;
DROP TABLE IF EXISTS FollowNotification CASCADE;
DROP TABLE IF EXISTS CommentNotification CASCADE;
DROP TABLE IF EXISTS VoteNotification CASCADE;
DROP TABLE IF EXISTS Replies CASCADE;
DROP TABLE IF EXISTS Favorites CASCADE;
DROP TABLE IF EXISTS NewsTag CASCADE;
DROP TABLE IF EXISTS CommentReport CASCADE;
DROP TABLE IF EXISTS ArticleReport CASCADE;
DROP TABLE IF EXISTS UserReport CASCADE;
DROP TABLE IF EXISTS UserFollowTag CASCADE;
DROP TABLE IF EXISTS UserFollows CASCADE;
DROP TABLE IF EXISTS ReputationVote CASCADE;
DROP TABLE IF EXISTS Comment CASCADE;
DROP TABLE IF EXISTS NewsArticle CASCADE;
DROP TABLE IF EXISTS Tag CASCADE;
DROP TABLE IF EXISTS Users CASCADE;

-- R01: Users Table
CREATE TABLE Users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(512) NOT NULL,
    bio TEXT,
    reputation INT NOT NULL DEFAULT 0,
    remember_token VARCHAR,
    role INT NOT NULL
);

-- R07: Tag Table
CREATE TABLE Tag (
    tag_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    accepted INT NOT NULL DEFAULT 0
);

-- R09: NewsArticle Table
CREATE TABLE NewsArticle (
    newsArticle_id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reputation INT DEFAULT 1,
    creator_id INT,
    FOREIGN KEY (creator_id) REFERENCES Users (user_id)
);

-- R11: Comment Table
CREATE TABLE Comment (
    comment_id SERIAL PRIMARY KEY,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reputation INT,
    article_id INT,
    commentCreator_id INT,
    FOREIGN KEY (article_id) REFERENCES NewsArticle (newsArticle_id) ON DELETE CASCADE,
    FOREIGN KEY (commentCreator_id) REFERENCES Users (user_id)
);

-- R13: ReputationVote Table
CREATE TABLE ReputationVote (
    reputationVote_id SERIAL PRIMARY KEY,
    score INT NOT NULL CHECK(score = 1 OR score = -1),
    voter_id INT,
    voteType VoteTypes NOT NULL,
    FOREIGN KEY (voter_id) REFERENCES Users (user_id)
);

-- R02: UserFollows Table
CREATE TABLE UserFollows (
    follower_id INT,
    followed_id INT,
    PRIMARY KEY (follower_id, followed_id),
    FOREIGN KEY (follower_id) REFERENCES Users (user_id),
    FOREIGN KEY (followed_id) REFERENCES Users (user_id)
);

-- R03: UserFollowTag Table
CREATE TABLE UserFollowTag (
    user_id INT,
    tag_id INT,
    PRIMARY KEY (user_id, tag_id),
    FOREIGN KEY (user_id) REFERENCES Users (user_id),
    FOREIGN KEY (tag_id) REFERENCES Tag (tag_id)
);

-- R04: UserReport Table
CREATE TABLE UserReport (
    userReport_id SERIAL PRIMARY KEY,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reporter_id INT,
    reported_id INT,
    FOREIGN KEY (reporter_id) REFERENCES Users (user_id),
    FOREIGN KEY (reported_id) REFERENCES Users (user_id) ON DELETE CASCADE
);

-- R05: ArticleReport Table
CREATE TABLE ArticleReport (
    articleReport_id SERIAL PRIMARY KEY,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reporter_id INT,
    reported_id INT,
    FOREIGN KEY (reporter_id) REFERENCES Users (user_id),
    FOREIGN KEY (reported_id) REFERENCES NewsArticle (newsArticle_id) ON DELETE CASCADE
);

-- R06: CommentReport Table
CREATE TABLE CommentReport (
    commentReport_id SERIAL PRIMARY KEY,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reporter_id INT,
    reported_id INT,
    FOREIGN KEY (reporter_id) REFERENCES Users (user_id),
    FOREIGN KEY (reported_id) REFERENCES Comment (comment_id) ON DELETE CASCADE
);

-- R08: NewsTag Table
CREATE TABLE NewsTag (
    tag_id INT,
    newsArticle_id INT,
    PRIMARY KEY (tag_id, newsArticle_id),
    FOREIGN KEY (tag_id) REFERENCES Tag (tag_id),
    FOREIGN KEY (newsArticle_id) REFERENCES NewsArticle (newsArticle_id)
);

-- R10: Favorites Table
CREATE TABLE Favorites (
    user_id INT,
    article_id INT,
    PRIMARY KEY (user_id, article_id),
    FOREIGN KEY (user_id) REFERENCES Users (user_id),
    FOREIGN KEY (article_id) REFERENCES NewsArticle (newsArticle_id)
);

-- R12: Replies Table
CREATE TABLE Replies (
    parent_id INT,
    child_id INT,
    PRIMARY KEY (parent_id, child_id),
    FOREIGN KEY (parent_id) REFERENCES Comment (comment_id),
    FOREIGN KEY (child_id) REFERENCES Comment (comment_id)
);

-- R14: ArticleVote Table
CREATE TABLE ArticleVote (
    vote_id INT,
    content_id INT,
    PRIMARY KEY (vote_id, content_id),
    FOREIGN KEY (vote_id) REFERENCES ReputationVote (reputationVote_id),
    FOREIGN KEY (content_id) REFERENCES NewsArticle (newsArticle_id)
);

-- R15: CommentVote Table
CREATE TABLE CommentVote (
    vote_id INT,
    content_id INT,
    PRIMARY KEY (vote_id, content_id),
    FOREIGN KEY (vote_id) REFERENCES ReputationVote (reputationVote_id),
    FOREIGN KEY (content_id) REFERENCES Comment (comment_id)
);

-- R16: FollowNotification Table
CREATE TABLE FollowNotification (
    followNotification_id SERIAL PRIMARY KEY,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body TEXT NOT NULL,
    read BOOLEAN NOT NULL,
    notified_id INT,
    follower_id INT,
    FOREIGN KEY (notified_id) REFERENCES Users (user_id),
    FOREIGN KEY (follower_id) REFERENCES Users (user_id)
);

-- R17: VoteNotification Table
CREATE TABLE VoteNotification (
    voteNotification_id SERIAL PRIMARY KEY,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body TEXT NOT NULL,
    read BOOLEAN NOT NULL,
    notified_id INT,
    vote_id INT,
    FOREIGN KEY (notified_id) REFERENCES Users (user_id),
    FOREIGN KEY (vote_id) REFERENCES ReputationVote (reputationVote_id)
);

-- R18: CommentNotification Table
CREATE TABLE CommentNotification (
    commentNotification_id SERIAL PRIMARY KEY,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body TEXT NOT NULL,
    read BOOLEAN NOT NULL,
    notified_id INT,
    comment_id INT,
    FOREIGN KEY (notified_id) REFERENCES Users (user_id),
    FOREIGN KEY (comment_id) REFERENCES Comment (comment_id)
);

-----------------------------------------
-- INDEXES
-----------------------------------------

DROP INDEX IF EXISTS idx_user_username_email;
DROP INDEX IF EXISTS idx_newsarticle_reputation_datetime;
DROP INDEX IF EXISTS idx_articlevote_content;

CREATE INDEX idx_user_username_email ON Users USING btree (username, email);

CREATE INDEX idx_newsarticle_reputation_datetime ON NewsArticle USING btree (reputation, date_time DESC);

CREATE INDEX idx_articlevote_content ON ArticleVote USING btree (content_id);

-- FTS INDEXES

-- Add column to store computed ts_vectors
ALTER TABLE Users ADD COLUMN tsvectors TSVECTOR;

-- Drop the function if it already exists (to avoid conflicts)
DROP FUNCTION IF EXISTS users_search_update CASCADE;

-- Create a function to automatically update ts_vectors
CREATE OR REPLACE FUNCTION users_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.username, '')), 'A') ||
                     setweight(to_tsvector('english', coalesce(NEW.email, '')), 'B');
  ELSIF TG_OP = 'UPDATE' THEN
    IF NEW.username IS DISTINCT FROM OLD.username OR NEW.email IS DISTINCT FROM OLD.email THEN
      NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.username, '')), 'A') ||
                       setweight(to_tsvector('english', coalesce(NEW.email, '')), 'B');
    END IF;
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

-- Create trigger before insert or update on Users
DROP TRIGGER IF EXISTS users_search_update ON Users; -- Drop if exists to avoid conflicts
CREATE TRIGGER users_search_update
BEFORE INSERT OR UPDATE ON Users
FOR EACH ROW EXECUTE PROCEDURE users_search_update();

-- Finally, create a GIN index for tsvectors
CREATE INDEX user_search_idx ON Users USING GIN (tsvectors);

-- Add column to store computed ts_vectors
ALTER TABLE Tag ADD COLUMN tsvectors TSVECTOR;

-- Drop the function if it already exists (to avoid conflicts)
DROP FUNCTION IF EXISTS tag_search_update CASCADE;

-- Create a function to automatically update ts_vectors
CREATE OR REPLACE FUNCTION tag_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.name, '')), 'A');
  ELSIF TG_OP = 'UPDATE' THEN
    IF NEW.name IS DISTINCT FROM OLD.name THEN
      NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.name, '')), 'A');
    END IF;
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

-- Create trigger before insert or update on Tag
DROP TRIGGER IF EXISTS tag_search_update ON Tag; -- Drop if exists to avoid conflicts
CREATE TRIGGER tag_search_update
BEFORE INSERT OR UPDATE ON Tag
FOR EACH ROW EXECUTE PROCEDURE tag_search_update();

-- Finally, create a GIN index for tsvectors
CREATE INDEX tag_search_idx ON Tag USING GIN (tsvectors);
-- Add column to store computed ts_vectors
ALTER TABLE NewsArticle ADD COLUMN tsvectors TSVECTOR;

-- Drop the function if it already exists (to avoid conflicts)
DROP FUNCTION IF EXISTS newsarticle_search_update CASCADE;

-- Create a function to automatically update ts_vectors
CREATE OR REPLACE FUNCTION newsarticle_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.title, '')), 'A') ||
                     setweight(to_tsvector('english', coalesce(NEW.body, '')), 'B');
  ELSIF TG_OP = 'UPDATE' THEN
    IF NEW.title IS DISTINCT FROM OLD.title OR NEW.body IS DISTINCT FROM OLD.body THEN
      NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.title, '')), 'A') ||
                       setweight(to_tsvector('english', coalesce(NEW.body, '')), 'B');
    END IF;
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

-- Create trigger before insert or update on NewsArticle
DROP TRIGGER IF EXISTS newsarticle_search_update ON NewsArticle; -- Drop if exists to avoid conflicts
CREATE TRIGGER newsarticle_search_update
BEFORE INSERT OR UPDATE ON NewsArticle
FOR EACH ROW EXECUTE PROCEDURE newsarticle_search_update();

-- Finally, create a GIN index for tsvectors
CREATE INDEX newsarticle_search_idx ON NewsArticle USING GIN (tsvectors);
-- Add column to store computed ts_vectors
ALTER TABLE Comment ADD COLUMN tsvectors TSVECTOR;

-- Drop the function if it already exists (to avoid conflicts)
DROP FUNCTION IF EXISTS comment_search_update CASCADE;

-- Create a function to automatically update ts_vectors
CREATE OR REPLACE FUNCTION comment_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.body, '')), 'A');
  ELSIF TG_OP = 'UPDATE' THEN
    IF NEW.body IS DISTINCT FROM OLD.body THEN
      NEW.tsvectors := setweight(to_tsvector('english', coalesce(NEW.body, '')), 'A');
    END IF;
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

-- Create trigger before insert or update on Comment
DROP TRIGGER IF EXISTS comment_search_update ON Comment; -- Drop if exists to avoid conflicts
CREATE TRIGGER comment_search_update
BEFORE INSERT OR UPDATE ON Comment
FOR EACH ROW EXECUTE PROCEDURE comment_search_update();

-- Finally, create a GIN index for tsvectors
CREATE INDEX comment_search_idx ON Comment USING GIN (tsvectors);

-----------------------------------------
-- TRIGGER FUNCTIONS
-----------------------------------------

CREATE OR REPLACE FUNCTION update_user_reputation_after_article_vote()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE Users 
    SET reputation = reputation + (SELECT score FROM ReputationVote WHERE reputationVote_id = NEW.vote_id) 
    WHERE user_id = (SELECT creator_id FROM NewsArticle WHERE newsArticle_id = NEW.content_id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_user_reputation_after_comment_vote()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE Users 
    SET reputation = reputation + (SELECT score FROM ReputationVote WHERE reputationVote_id = NEW.vote_id) 
    WHERE user_id = (SELECT commentCreator_id FROM Comment WHERE comment_id = NEW.content_id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION notify_on_new_follower()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO FollowNotification(body, read, notified_id, follower_id) VALUES ('You have a new follower!', FALSE, NEW.followed_id, NEW.follower_id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION notify_on_new_article_vote()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO VoteNotification(body, read, notified_id, vote_id) VALUES ('Your article received a new vote!', FALSE, (SELECT creator_id FROM NewsArticle WHERE newsArticle_id = NEW.content_id), NEW.vote_id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION notify_on_new_comment_vote()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO VoteNotification(body, read, notified_id, vote_id) VALUES ('Your comment received a new vote!', FALSE, (SELECT commentCreator_id FROM Comment WHERE comment_id = NEW.content_id), NEW.vote_id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION notify_on_new_comment()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO CommentNotification(body, read, notified_id, comment_id) VALUES ('Your article received a new comment!', FALSE, (SELECT creator_id FROM NewsArticle WHERE newsArticle_id = NEW.article_id), NEW.comment_id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION auto_follow_tags_on_article_creation()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO UserFollowTag(user_id, tag_id) SELECT creator_id, NEW.tag_id FROM NewsArticle WHERE newsArticle_id = NEW.newsArticle_id ON CONFLICT DO NOTHING;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_article_reputation()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE NewsArticle 
    SET reputation = reputation + (SELECT score FROM ReputationVote WHERE reputationVote_id = NEW.vote_id) 
    WHERE newsArticle_id = NEW.content_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_comment_reputation()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE Comment 
    SET reputation = reputation + (SELECT score FROM ReputationVote WHERE reputationVote_id = NEW.vote_id) 
    WHERE comment_id = NEW.content_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION auto_upvote_after_article_creation()
RETURNS TRIGGER AS $$
DECLARE
    new_vote_id INT;
BEGIN

  INSERT INTO ReputationVote (score, voter_id, voteType)
  VALUES (1, NEW.creator_id, 'Upvote')
  RETURNING reputationVote_id INTO new_vote_id;
  
  INSERT INTO ArticleVote (vote_id, content_id)
  VALUES (new_vote_id, NEW.newsArticle_id);
  
  UPDATE Users
  SET reputation = reputation + 1
  WHERE user_id = NEW.creator_id;

  UPDATE NewsArticle
  SET reputation = reputation + 1
  WHERE newsArticle_id = NEW.newsArticle_id;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-----------------------------------------
-- TRIGGERS
-----------------------------------------

CREATE TRIGGER trg_update_user_reputation_after_article_vote
AFTER INSERT ON ArticleVote
FOR EACH ROW
EXECUTE FUNCTION update_user_reputation_after_article_vote();

CREATE TRIGGER trg_update_user_reputation_after_comment_vote
AFTER INSERT ON CommentVote
FOR EACH ROW
EXECUTE FUNCTION update_user_reputation_after_comment_vote();

CREATE TRIGGER trg_notify_on_new_follower
AFTER INSERT ON UserFollows
FOR EACH ROW
EXECUTE FUNCTION notify_on_new_follower();

CREATE TRIGGER trg_notify_on_new_article_vote
AFTER INSERT ON ArticleVote
FOR EACH ROW
EXECUTE FUNCTION notify_on_new_article_vote();

CREATE TRIGGER trg_notify_on_new_comment_vote
AFTER INSERT ON CommentVote
FOR EACH ROW
EXECUTE FUNCTION notify_on_new_comment_vote();

CREATE TRIGGER trg_notify_on_new_comment
AFTER INSERT ON Comment
FOR EACH ROW
EXECUTE FUNCTION notify_on_new_comment();

CREATE TRIGGER trg_auto_follow_tags_on_article_creation
AFTER INSERT ON NewsTag
FOR EACH ROW
EXECUTE FUNCTION auto_follow_tags_on_article_creation();

CREATE TRIGGER trg_update_article_reputation
AFTER INSERT ON ArticleVote
FOR EACH ROW
EXECUTE FUNCTION update_article_reputation();

CREATE TRIGGER trg_update_comment_reputation
AFTER INSERT ON CommentVote
FOR EACH ROW
EXECUTE FUNCTION update_comment_reputation();

CREATE TRIGGER trg_auto_upvote_after_article_creation
AFTER INSERT ON NewsArticle
FOR EACH ROW
EXECUTE FUNCTION auto_upvote_after_article_creation();
-----------------------------------------
-- END
-----------------------------------------