-- Insert test users (common users and a system manager)
INSERT INTO Users (username, email, password_hash, bio, reputation, role)
VALUES
    ('user1', 'user1@example.com', 'password_hash_1', 'User 1 Bio', 100, 2), -- System Manager
    ('user2', 'user2@example.com', 'password_hash_2', 'User 2 Bio', 50, 1),  -- Common User
    ('user3', 'user3@example.com', 'password_hash_3', 'User 3 Bio', 30, 1);

-- Insert test tags
INSERT INTO Tag (name, accepted)
VALUES
    ('Technology', 1),
    ('Science', 1),
    ('Programming', 1);

-- Insert test news articles
INSERT INTO NewsArticle (title, body, date_time, creator_id)
VALUES
    ('Tech News 1', 'Content of Tech News 1', NOW() - interval '1 day', 2),
    ('Tech News 2', 'Content of Tech News 2', NOW() - interval '2 days', 3),
    ('Science News 1', 'Content of Science News 1', NOW() - interval '3 days', 2);

-- Insert test comments
INSERT INTO Comment (body, date_time, article_id, commentCreator_id)
VALUES
    ('Comment on Tech News 1', NOW() - interval '1 hour', 1, 3),
    ('Comment on Tech News 2', NOW() - interval '2 hours', 2, 2),
    ('Comment on Science News 1', NOW() - interval '3 hours', 3, 3);

-- Insert test tags for news articles
INSERT INTO NewsTag (tag_id, newsArticle_id)
VALUES
    (1, 1),
    (1, 2),
    (2, 3);

-- Insert test favorites
INSERT INTO Favorites (user_id, article_id)
VALUES
    (2, 1),
    (3, 2),
    (2, 3);

-- Insert test user follows
INSERT INTO UserFollows (follower_id, followed_id)
VALUES
    (2, 3),
    (3, 2);

-- Insert test user follows tags
INSERT INTO UserFollowTag (user_id, tag_id)
VALUES
    (2, 3),
    (3, 2);

-- Insert test reputation votes for articles and comments
INSERT INTO ReputationVote (score, voter_id, voteType)
VALUES
    (1, 2, 'Upvote'),
    (-1, 3, 'Downvote'),
    (1, 3, 'Upvote'),
    (1, 3, 'Upvote');


-- Insert test article votes
INSERT INTO ArticleVote (vote_id, content_id)
VALUES
    (1, 2),
    (2, 1);

-- Insert test comment votes
INSERT INTO CommentVote (vote_id, content_id)
VALUES
    (3, 1),
    (4, 3);

-- Insert test follow notifications
INSERT INTO FollowNotification (date_time, body, read, notified_id, follower_id)
VALUES
    (NOW() - interval '1 hour', 'You have a new follower!', FALSE, 2, 3);

-- Insert test vote notifications for articles and comments
INSERT INTO VoteNotification (date_time, body, read, notified_id, vote_id)
VALUES
    (NOW() - interval '1 hour', 'Your article received a new vote!', FALSE, 2, 1),
    (NOW() - interval '2 hours', 'Your comment received a new vote!', FALSE, 3, 3);

-- Insert test comment notifications
INSERT INTO CommentNotification (date_time, body, read, notified_id, comment_id)
VALUES
    (NOW() - interval '1 hour', 'Your article received a new comment!', FALSE, 2, 1),
    (NOW() - interval '2 hours', 'Your comment received a new comment!', FALSE, 3, 3);
