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

CREATE TABLE Users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(512) NOT NULL,
    remember_token VARCHAR DEFAULT NULL, 
    bio TEXT,
    profile_image VARCHAR,
    reputation INT NOT NULL DEFAULT 0,
    role INT NOT NULL
);

CREATE TABLE UnblockAppeal (
    appeal_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    appeal_text TEXT NOT NULL,
    appeal_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_resolved BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES Users (user_id) ON DELETE CASCADE
);

CREATE TABLE Tag (
    tag_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    accepted INT NOT NULL DEFAULT 0
);

CREATE TABLE NewsArticle (
    newsArticle_id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reputation INT DEFAULT 0,
    creator_id INT,
    article_image VARCHAR,
    FOREIGN KEY (creator_id) REFERENCES Users (user_id) ON DELETE SET NULL
);

CREATE TABLE Comment (
    comment_id SERIAL PRIMARY KEY,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reputation INT NOT NULL DEFAULT 0,
    article_id INT,
    commentCreator_id INT,
    FOREIGN KEY (article_id) REFERENCES NewsArticle (newsArticle_id) ON DELETE CASCADE,
    FOREIGN KEY (commentCreator_id) REFERENCES Users (user_id) ON DELETE SET NULL
);

CREATE TABLE ReputationVote (
    reputationVote_id SERIAL PRIMARY KEY,
    score INT NOT NULL CHECK(score = 1 OR score = -1),
    voter_id INT,
    voteType VoteTypes NOT NULL,
    FOREIGN KEY (voter_id) REFERENCES Users (user_id) ON DELETE SET NULL
);

CREATE TABLE UserFollows (
    follower_id INT,
    followed_id INT,
    PRIMARY KEY (follower_id, followed_id),
    FOREIGN KEY (follower_id) REFERENCES Users (user_id) ON DELETE CASCADE,
    FOREIGN KEY (followed_id) REFERENCES Users (user_id) ON DELETE CASCADE
);

CREATE TABLE UserFollowTag (
    user_id INT,
    tag_id INT,
    PRIMARY KEY (user_id, tag_id),
    FOREIGN KEY (user_id) REFERENCES Users (user_id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES Tag (tag_id)
);

CREATE TABLE UserReport (
    userReport_id SERIAL PRIMARY KEY,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reporter_id INT,
    reported_id INT,
    FOREIGN KEY (reporter_id) REFERENCES Users (user_id) ON DELETE SET NULL,
    FOREIGN KEY (reported_id) REFERENCES Users (user_id) ON DELETE CASCADE
);

CREATE TABLE ArticleReport (
    articleReport_id SERIAL PRIMARY KEY,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reporter_id INT,
    reported_id INT,
    FOREIGN KEY (reporter_id) REFERENCES Users (user_id) ON DELETE SET NULL,
    FOREIGN KEY (reported_id) REFERENCES NewsArticle (newsArticle_id) ON DELETE CASCADE
);

CREATE TABLE CommentReport (
    commentReport_id SERIAL PRIMARY KEY,
    body TEXT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reporter_id INT,
    reported_id INT,
    FOREIGN KEY (reporter_id) REFERENCES Users (user_id) ON DELETE SET NULL,
    FOREIGN KEY (reported_id) REFERENCES Comment (comment_id) ON DELETE CASCADE
);

CREATE TABLE NewsTag (
    tag_id INT,
    newsArticle_id INT,
    PRIMARY KEY (tag_id, newsArticle_id),
    FOREIGN KEY (tag_id) REFERENCES Tag (tag_id),
    FOREIGN KEY (newsArticle_id) REFERENCES NewsArticle (newsArticle_id) ON DELETE CASCADE
);

CREATE TABLE Favorites (
    user_id INT,
    article_id INT,
    PRIMARY KEY (user_id, article_id),
    FOREIGN KEY (user_id) REFERENCES Users (user_id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES NewsArticle (newsArticle_id) ON DELETE CASCADE
);

CREATE TABLE Replies (
    parent_id INT,
    child_id INT,
    PRIMARY KEY (parent_id, child_id),
    FOREIGN KEY (parent_id) REFERENCES Comment (comment_id) ON DELETE CASCADE , 
    FOREIGN KEY (child_id) REFERENCES Comment (comment_id) ON DELETE CASCADE
);

CREATE TABLE ArticleVote (
    vote_id INT,
    content_id INT,
    PRIMARY KEY (vote_id, content_id),
    FOREIGN KEY (vote_id) REFERENCES ReputationVote (reputationVote_id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES NewsArticle (newsArticle_id) ON DELETE CASCADE
);

CREATE TABLE CommentVote (
    vote_id INT,
    content_id INT,
    PRIMARY KEY (vote_id, content_id),
    FOREIGN KEY (vote_id) REFERENCES ReputationVote (reputationVote_id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES Comment (comment_id) ON DELETE CASCADE
);

CREATE TABLE FollowNotification (
    followNotification_id SERIAL PRIMARY KEY,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body TEXT NOT NULL,
    read BOOLEAN NOT NULL,
    notified_id INT,
    follower_id INT,
    FOREIGN KEY (notified_id) REFERENCES Users (user_id) ON DELETE SET NULL,
    FOREIGN KEY (follower_id) REFERENCES Users (user_id) ON DELETE SET NULL
);

CREATE TABLE VoteNotification (
    voteNotification_id SERIAL PRIMARY KEY,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body TEXT NOT NULL,
    read BOOLEAN NOT NULL,
    notified_id INT,
    vote_id INT,
    FOREIGN KEY (notified_id) REFERENCES Users (user_id) ON DELETE SET NULL,
    FOREIGN KEY (vote_id) REFERENCES ReputationVote (reputationVote_id) ON DELETE CASCADE
);

CREATE TABLE CommentNotification (
    commentNotification_id SERIAL PRIMARY KEY,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    body TEXT NOT NULL,
    read BOOLEAN NOT NULL,
    notified_id INT,
    comment_id INT,
    FOREIGN KEY (notified_id) REFERENCES Users (user_id) ON DELETE SET NULL,
    FOREIGN KEY (comment_id) REFERENCES Comment (comment_id) ON DELETE CASCADE
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

ALTER TABLE Users ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS users_search_update CASCADE;

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

DROP TRIGGER IF EXISTS users_search_update ON Users;
CREATE TRIGGER users_search_update
BEFORE INSERT OR UPDATE ON Users
FOR EACH ROW EXECUTE PROCEDURE users_search_update();

CREATE INDEX user_search_idx ON Users USING GIN (tsvectors);

ALTER TABLE Tag ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS tag_search_update CASCADE;

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

DROP TRIGGER IF EXISTS tag_search_update ON Tag;
CREATE TRIGGER tag_search_update
BEFORE INSERT OR UPDATE ON Tag
FOR EACH ROW EXECUTE PROCEDURE tag_search_update();

CREATE INDEX tag_search_idx ON Tag USING GIN (tsvectors);

ALTER TABLE NewsArticle ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS newsarticle_search_update CASCADE;

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

DROP TRIGGER IF EXISTS newsarticle_search_update ON NewsArticle; 
CREATE TRIGGER newsarticle_search_update
BEFORE INSERT OR UPDATE ON NewsArticle
FOR EACH ROW EXECUTE PROCEDURE newsarticle_search_update();

CREATE INDEX newsarticle_search_idx ON NewsArticle USING GIN (tsvectors);

ALTER TABLE Comment ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS comment_search_update CASCADE;

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

DROP TRIGGER IF EXISTS comment_search_update ON Comment; 
CREATE TRIGGER comment_search_update
BEFORE INSERT OR UPDATE ON Comment
FOR EACH ROW EXECUTE PROCEDURE comment_search_update();

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

CREATE OR REPLACE FUNCTION decrease_article_reputation()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE NewsArticle 
    SET reputation = reputation - OLD.score
    WHERE newsArticle_id = (SELECT content_id FROM ArticleVote WHERE vote_id = OLD.reputationVote_id);
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION decrease_comment_reputation()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE Comment 
    SET reputation = reputation - OLD.score
    WHERE comment_id = (SELECT content_id FROM CommentVote WHERE vote_id = OLD.reputationVote_id);
    RETURN OLD;
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

CREATE TRIGGER trg_decrease_article_reputation
BEFORE DELETE ON ReputationVote
FOR EACH ROW
EXECUTE FUNCTION decrease_article_reputation();

CREATE TRIGGER trg_decrease_comment_reputation
BEFORE DELETE ON ReputationVote
FOR EACH ROW
EXECUTE FUNCTION decrease_comment_reputation();

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

INSERT INTO Users (username, email, password, bio, reputation, role)
VALUES
    ('joaoadmin', 'user1@example.com', '$2y$10$7H0zogo5/6062vRYBOWY9u5OxNSmzFoXMNB897iITRUNiw/N5lbs6', 'I am the first admin of this website', 100, 2), -- System Manager
    ('tozeutilizador', 'user2@example.com', '$2y$10$JHcQczJ.GGLbXe15m.1eNu/t23UBTNeKBjCkPTqWwuike7UwsHsW.', 'I was the first use of this website', 50, 1),  -- Common User
    ('daniel', 'user3@example.com', '$2y$10$GDvBI0Jgz2QaSCUwvaG4keoLR2XZZyaTIhsd/2UPj4oVJF0/PzhWO', 'Im Daniel', 30, 1),
    ('hwiley', 'andrewbrooks@velazquez.com', 'd1o"eH`GRIXD', 'Exploring the intersection of tech and humanity.', 27, 1),
    ('msalinas', 'ginaalexander@maldonado-carpenter.com', 'AF$ZR)n3c+Lv^z}', 'Innovator and problem-solver in the tech space.', 14, 1),
    ('robin91', 'wfloyd@perkins.com', 'cPQ9uhysf}A3"u', 'Tech junkie. Creator at heart.', 62, 4),
    ('griffithdonald', 'holtstacey@fleming-campbell.net', 'gPys&5L%!58O', 'Tech enthusiast and lifelong learner.', 17, 1),
    ('andrewtravis', 'hperez@gmail.com', 'BK<bE[0rjDmldx<=', 'Building the future with technology.', 16, 1),
    ('donna64', 'ann14@gmail.com', '~7#q{$G:C%lsu%', 'Lover of all things tech. Always learning.', 94, 1),
    ('lorirogers', 'millersteven@huynh-scott.com', 'J!9`RaTvuW,n1fa0', 'Exploring the intersection of tech and humanity.', 30, 1),
    ('hutchinsonshirley', 'vicki79@yahoo.com', 'B8=sqkx*:?@', 'Committed to making technology accessible to all.', 52, 1),
    ('ryanmcguire', 'ianwalters@gmail.com', 'u"so]di]md', 'Passionate about tech and coding.', 41, 1),
    ('danielle18', 'donna51@gmail.com', 'dnAZE@Q9ec:', 'Exploring the intersection of tech and humanity.', 76, 1),
    ('travis21', 'benjamin31@hotmail.com', 'g;6Yj%1m2^kbAiJ(', 'Advocate for ethical tech and open source.', 68, 4),
    ('amygreen', 'mariahduran@hotmail.com', 'kXVc|mz%|<;K', 'Passionate about tech and coding.', 86, 1),
    ('vfriedman', 'josephschmidt@curtis-bryan.info', 'm.qY*^%zW5~J', 'Building the future with technology.', 54, 1),
    ('bryanmaxwell', 'elizabeth99@hotmail.com', 'K9K-H$B{&D', 'Committed to making technology accessible to all.', 59, 1),
    ('jamesjohnston', 'graycrystal@hotmail.com', ',)VHZ5f!lq', 'Committed to making technology accessible to all.', 6, 1),
    ('padillastephen', 'leah13@gmail.com', 'Mr=YhHJf4A<0jfQ', 'Tech enthusiast and lifelong learner.', 19, 1),
    ('dianenewton', 'rodrigueznathan@thomas.com', '6^)PA!3,a=j?bH1', 'Coding my way through lifes challenges.', 67, 4),
    ('lewischristopher', 'alexandralambert@page.com', 'f?(!C?Wrow', 'Tech enthusiast and lifelong learner.', 9, 1),
    ('nbrown', 'toddshelly@kelley.com', '.C")5do>}Vp0=~', 'Committed to making technology accessible to all.', 88, 1),
    ('heatherharris', 'geralddean@adams.com', 'uZ[{X870j#', 'Committed to making technology accessible to all.', 68, 1),
    ('freemanpatricia', 'marknelson@vargas.com', '2t>Lt.PB{Fk|_J', 'Building the future with technology.', 41, 1),
    ('jenniferburnett', 'breid@garcia.org', 'pE+;(m"RW#>', 'Tech enthusiast and lifelong learner.', 97, 1);

INSERT INTO UnblockAppeal (user_id, appeal_text, is_resolved)
VALUES
    (6, 'I believe my account was blocked by mistake. Please review.', FALSE),
    (14, 'I have read the community guidelines and will adhere to them going forward. Please unblock my account.', FALSE),
    (20, 'I was unaware of the rule I broke. I apologize and request that my account be unblocked.', FALSE);

INSERT INTO Tag (name, accepted)
VALUES
    ('Technology', 1),
    ('Science', 1),
    ('Programming', 1),
    ('Space Tech', 1),
    ('Computer Science', 0),
    ('AI',0);

INSERT INTO NewsArticle (title, body, date_time, creator_id)
VALUES
    ('Mapping Disease Spread with Innovative App Technology', 'A new app is using crowd-sourced data to map the spread of diseases in real-time.', '2023-11-09 17:07:11', 19),
    ('Innovative Water Purification Techniques Using Nanotech', 'Scientists have developed a more efficient method of water purification using nanotechnology.', '2023-11-08 14:11:13', 13),
    ('Securing Digital Transactions: The Power of Blockchain', 'A new study reveals the potential of blockchain in securing online transactions.', '2023-10-28 08:39:17', 5),
    ('The Road Ahead: Autonomous Vehicles in Urban Testing', 'Autonomous vehicles are being tested in urban environments, showing promise for future transportation.', '2023-11-04 11:04:48', 16),
    ('Immersive Gaming: The Virtual Reality Revolution', 'Virtual reality technology is transforming the gaming industry with immersive experiences.', '2023-10-30 03:47:25', 18),
    ('Biotechnology Breakthroughs Promise Personalized Medicine', 'Advancements in biotechnology are paving the way for personalized medicine treatments.', '2023-10-23 06:36:38', 24),
    ('Quantum Computing: The Next Frontier in Processing Power', 'A breakthrough in quantum computing sets the stage for unprecedented computational power.', '2023-11-02 07:06:27', 13),
    ('Machine Learning: Forecasting the Weather with Precision', 'Advances in machine learning are enabling more accurate predictions in weather forecasting.', '2023-10-22 05:34:37', 18),
    ('Robotic Precision: The Future of Surgical Procedures', 'Cutting-edge robotics are being used to perform complex surgeries with precision.', '2023-11-19 23:14:00', 5),
    ('The Rise of Wearable Tech for Health Monitoring', 'Innovative wearable tech is becoming increasingly popular for health monitoring.', '2023-11-05 02:42:26', 24),
    ('New Breakthrough in Solar Energy', 'Details about the latest advancements in solar panels.', '2023-12-05 02:48:26', 1),
    ('Healthcare Revolution: Telemedicine', 'How telemedicine is transforming healthcare delivery.', '2023-12-12 04:25:26', 2),
    ('Global Economies Embrace Cryptocurrency', 'Cryptocurrency is becoming more prevalent in global trade.', '2023-11-26 14:15:43', 3);

INSERT INTO Comment (body, date_time, article_id, commentCreator_id)
VALUES
    ('The disease mapping app is a game-changer in tracking outbreaks!', '2023-11-07 18:30:00', 1, 2),
    ('I''m excited to see how this app will help us monitor diseases!', '2023-11-07 19:15:00', 1, 3),
    ('Great job on the disease spread mapping app!', '2023-11-07 20:00:00', 1, 4),
    ('Nanotech water purification is fascinating! A step towards cleaner water.', '2023-11-08 15:45:00', 2, 5),
    ('The future of water purification looks promising with nanotechnology.', '2023-11-08 16:30:00', 2, 6),
    ('I''m impressed by the innovation in water purification techniques.', '2023-11-08 17:15:00', 2, 7),
    ('Blockchain is indeed a powerful tool for securing digital transactions.', '2023-10-28 09:15:00', 3, 8),
    ('The study on blockchain''s potential in online security is eye-opening.', '2023-10-28 10:00:00', 3, 9),
    ('I see a bright future ahead with blockchain technology!', '2023-10-28 10:45:00', 3, 10),
    ('Autonomous vehicles in urban testing show great promise!', '2023-11-04 11:30:00', 4, 11),
    ('I can''t wait for self-driving cars to become a reality in our cities!', '2023-11-04 12:15:00', 4, 12),
    ('The future of transportation looks exciting with autonomous vehicles.', '2023-11-04 13:00:00', 4, 13),
    ('Immersive gaming experiences with virtual reality are a blast!', '2023-10-30 03:00:00', 5, 14),
    ('Virtual reality is transforming the gaming industry in incredible ways.', '2023-10-30 03:45:00', 5, 15),
    ('I''m having so much fun with immersive gaming technology!', '2023-10-30 04:30:00', 5, 16),
    ('Biotechnology breakthroughs are paving the way for personalized medicine.', '2023-10-23 06:30:00', 6, 17),
    ('Personalized medicine treatments are a revolution in healthcare!', '2023-10-23 07:15:00', 6, 18),
    ('I''m excited about the advancements in biotechnology and healthcare.', '2023-10-23 08:00:00', 6, 19),
    ('A breakthrough in quantum computing is set to revolutionize technology.', '2023-11-02 07:00:00', 7, 20),
    ('Quantum computing''s potential is mind-blowing!', '2023-11-02 07:45:00', 7, 21),
    ('I''m looking forward to the era of unprecedented computational power!', '2023-11-02 08:30:00', 7, 22),
    ('Machine learning is making weather forecasting more accurate than ever!', '2023-10-22 05:00:00', 8, 23),
    ('Accurate weather predictions are crucial for planning our activities.', '2023-10-22 05:45:00', 8, 24),
    ('Machine learning is transforming the world of weather forecasting!', '2023-10-22 06:30:00', 8, 25),
    ('Robotic precision in surgeries is a major advancement in healthcare.', '2023-11-19 22:45:00', 9, 21),
    ('Robotic surgeries are changing the way we approach complex procedures.', '2023-11-19 23:30:00', 9, 6),
    ('I''m amazed by the level of precision achieved with robotic surgery!', '2023-11-20 00:15:00', 9, 2),
    ('Wearable tech for health monitoring is revolutionizing healthcare!', '2023-11-05 02:15:00', 10, 3),
    ('I''m excited about the potential of wearable tech in monitoring our health.', '2023-11-05 03:00:00', 10, 4),
    ('Wearable tech is making it easier to stay on top of our well-being.', '2023-11-05 03:45:00', 10, 5);



INSERT INTO UserReport (body, date_time, reporter_id, reported_id)
VALUES 
('Inappropriate behavior in the forum', '2023-10-01 14:20:00', 1, 2),
('Spamming links to external sites', '2023-10-02 15:25:00', 3, 2),
('Spamming links to external sites', '2023-10-02 15:25:00', 3, 4);

INSERT INTO ArticleReport (body, date_time, reporter_id, reported_id)
VALUES 
('Misinformation in the article about health', '2023-10-05 09:15:00', 1, 1),
('Plagiarized content from another publication', '2023-10-06 10:05:00', 2, 2);

INSERT INTO CommentReport (body, date_time, reporter_id, reported_id)
VALUES 
('Offensive language used in comment', '2023-10-10 12:30:00', 1, 1),
('Harassment or bullying in comment thread', '2023-10-11 13:45:00', 2, 2);

INSERT INTO NewsTag (tag_id, newsArticle_id)
VALUES
    (1, 1),
    (1, 2),
    (2, 3);

INSERT INTO Favorites (user_id, article_id)
VALUES
    (2, 1),
    (3, 2),
    (2, 3);

INSERT INTO UserFollows (follower_id, followed_id)
VALUES
    (2, 3),
    (3, 2);

INSERT INTO UserFollowTag (user_id, tag_id)
VALUES
    (2, 3),
    (3, 2);

INSERT INTO ReputationVote (score, voter_id, voteType)
VALUES
    (1, 2, 'Upvote'),
    (-1, 3, 'Downvote'),
    (1, 3, 'Upvote'),
    (1, 3, 'Upvote');

INSERT INTO ArticleVote (vote_id, content_id)
VALUES
    (1, 2),
    (2, 1);

INSERT INTO CommentVote (vote_id, content_id)
VALUES
    (3, 1),
    (4, 3);

INSERT INTO FollowNotification (date_time, body, read, notified_id, follower_id)
VALUES
    (NOW() - interval '1 hour', 'You have a new follower!', FALSE, 2, 3);

INSERT INTO VoteNotification (date_time, body, read, notified_id, vote_id)
VALUES
    (NOW() - interval '1 hour', 'Your article received a new vote!', FALSE, 2, 1),
    (NOW() - interval '2 hours', 'Your comment received a new vote!', FALSE, 3, 3);

INSERT INTO CommentNotification (date_time, body, read, notified_id, comment_id)
VALUES
    (NOW() - interval '1 hour', 'Your article received a new comment!', FALSE, 2, 1),
    (NOW() - interval '2 hours', 'Your comment received a new comment!', FALSE, 3, 3);