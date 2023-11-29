drop table SCSponsorChannel;
drop table scSponsorRV;
drop table posted;
drop table userWatchChannel;
drop table userWatchLivestream;
drop table userProduceChannel;
drop table userProduceLivestream;
drop table adminiApproveRV;
drop table adminiMonitorLivestream;
drop table adminiMonitorForum;
drop table adminiMonitorAccount;
drop table sponsorCompany;
drop table adminiMain;
drop table adminiStatus;
drop table helpcenter;
drop table accountOwn;
drop table rvContain;
drop table LivestreamMain;
drop table LivestreamData;
drop table vipUserMain;
drop table vipUserData;
drop table ubcer;
drop table forum;
drop table channel;

CREATE TABLE channel (
                         ChannelID Varchar(10) PRIMARY KEY,
                         ChannelStatus Integer,
                         Showlist Varchar(100));
grant select on channel to public;

CREATE TABLE forum (
                       PostID Varchar(10) PRIMARY KEY,
                       ForumTag Varchar(20),
                       Author Varchar(20),
                       Title Varchar(20));
grant select on forum to public;

CREATE TABLE ubcer (
                       UserID Varchar(10) PRIMARY KEY,
                       UserName Varchar(20),
                       Subscription Varchar(1000));
grant select on ubcer to public;

CREATE TABLE vipUserData (
                             TimeLength Integer,
                             StartDate Date,
                             EndDate Date,
                             PRIMARY KEY (TimeLength, StartDate));
grant select on vipUserData to public;

CREATE TABLE vipUserMain (
                             UserID Varchar(10) PRIMARY KEY,
                             Timeperiod Integer,
                             Since Date,
                             FOREIGN KEY (UserID) REFERENCES ubcer
                                 ON DELETE CASCADE,
    --ON UPDATE CASCADE
                             FOREIGN KEY (Timeperiod, Since) REFERENCES vipUserData
                                 ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on vipUserMain to public;

CREATE TABLE LivestreamData (
                                LivestreamLength Integer,
                                StartTime varchar(40),
                                EndTime varchar(40),
                                PRIMARY KEY (LivestreamLength, StartTime));
grant select on LivestreamData to public;

-- change remember
CREATE TABLE LivestreamMain (
                                LiveStreamID Varchar(10) PRIMARY KEY,
                                Tag varchar(40),
                                PeriodLength Integer,
                                BeginTime varchar(40),
                                FOREIGN KEY (PeriodLength, BeginTime) REFERENCES LivestreamData
                                    ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on LivestreamMain to public;

CREATE TABLE rvContain (
                           VideoID Varchar(10),
                           ChannelID Varchar(10),
                           VideoLength varchar(30),
                           Tag Varchar(20),
                           CreateTime varchar(40),
                           VideoStatus Integer,
                           PRIMARY KEY(VideoID, ChannelID),
                           FOREIGN KEY (ChannelID) REFERENCES channel
                               ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on rvContain to public;

CREATE TABLE accountOwn (
                            AccountID Varchar(10),
                            UserID Varchar(10),
                            AccountStatus Integer,
                            Email Varchar(50),
                            AccountPassword Varchar(100),
                            BirthDate Date,
                            PRIMARY KEY (AccountID, UserID),
                            FOREIGN KEY (UserID) REFERENCES ubcer
                                ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on accountOwn to public;

CREATE TABLE helpcenter (
                            HelpIssueID Varchar(10) PRIMARY KEY,
                            ChannelID Varchar(10),
                            UserID Varchar(10),
                            VideoID Varchar(10),
                            AccountID Varchar(10),
                            PostID Varchar(10),
                            LiveStreamID Varchar(10),
                            ProcessStatus Integer,
                            IssueContent Varchar(1000),
                            IssueType Varchar(20),
                            FOREIGN KEY (VideoID, ChannelID) REFERENCES rvContain(VideoID, ChannelID)
                                ON DELETE CASCADE,
    -- ON UPDATE CASCADE,
                            FOREIGN KEY (AccountID, UserID) REFERENCES accountOwn(AccountID, UserID)
                                ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                            FOREIGN KEY (PostID) REFERENCES forum
                                ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                            FOREIGN KEY (LiveStreamID ) REFERENCES LivestreamMain
                                ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on helpcenter to public;

CREATE TABLE adminiStatus (
                              AdminName Varchar(20) PRIMARY KEY,
                              WorkStatus Integer);
grant select on adminiStatus to public;

CREATE TABLE adminiMain (
                            AdminID Varchar(10) PRIMARY KEY,
                            AdminstratorName Varchar(20),
                            FOREIGN KEY (AdminstratorName) REFERENCES adminiStatus
                                ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on adminiMain to public;

CREATE TABLE sponsorCompany (
                                SponsorID Varchar(10) PRIMARY KEY,
                                CompanyName Char(20),
                                Budget Integer);
grant select on sponsorCompany to public;

CREATE TABLE adminiMonitorAccount (
                                      AdminID Varchar(10),
                                      AccountID Varchar(10),
                                      UserID Varchar(10),
                                      PRIMARY KEY (AdminID, AccountID, UserID),
                                      FOREIGN KEY (AdminID) REFERENCES adminiMain
                                          ON DELETE CASCADE,
    --ON UPDATE CASCADE
                                      FOREIGN KEY (AccountID, UserID) REFERENCES accountOwn
                                          ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on adminiMonitorAccount to public;

CREATE TABLE adminiMonitorForum (
                                    AdminID Varchar(10),
                                    PostID Varchar(10),
                                    PRIMARY KEY (AdminID, PostID),
                                    FOREIGN KEY (AdminID) REFERENCES adminiMain
                                        ON DELETE CASCADE,
    --ON UPDATE CASCADE
                                    FOREIGN KEY (PostID ) REFERENCES forum
                                        ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on adminiMonitorForum to public;

CREATE TABLE adminiMonitorLivestream (
                                         AdminID Varchar(10),
                                         LiveStreamID Varchar(10),
                                         PRIMARY KEY (AdminID, LiveStreamID),
                                         FOREIGN KEY (AdminID) REFERENCES adminiMain
                                             ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                                         FOREIGN KEY (LiveStreamID) REFERENCES LivestreamMain
                                             ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on adminiMonitorLivestream to public;

CREATE TABLE adminiApproveRV (
                                 AdminID Varchar(10),
                                 VideoID Varchar(10),
                                 ChannelID Varchar(10),
                                 PRIMARY KEY (AdminID, VideoID, ChannelID),
                                 FOREIGN KEY (AdminID) REFERENCES adminiMain
                                     ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                                 FOREIGN KEY (VideoID, ChannelID) REFERENCES rvContain
                                     ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on adminiApproveRV to public;

CREATE TABLE userProduceLivestream (
                                       LiveStreamID Varchar(10),
                                       UserID Varchar(10),
                                       PRIMARY KEY (LiveStreamID, UserID),
                                       FOREIGN KEY (UserID) REFERENCES ubcer
                                           ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                                       FOREIGN KEY (LiveStreamID) REFERENCES LivestreamMain
                                           ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on userProduceLivestream to public;

CREATE TABLE userProduceChannel (
                                    ChannelID Varchar(10),
                                    UserID Varchar(10),
                                    PRIMARY KEY (ChannelID, UserID),
                                    FOREIGN KEY (UserID ) REFERENCES ubcer
                                        ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                                    FOREIGN KEY (ChannelID) REFERENCES channel
                                        ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on userProduceChannel to public;

CREATE TABLE userWatchLivestream (
                                     LiveStreamID Varchar(10),
                                     UserID Varchar(10),
                                     PRIMARY KEY (LiveStreamID, UserID),
                                     FOREIGN KEY (UserID) REFERENCES ubcer
                                         ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                                     FOREIGN KEY (LiveStreamID) REFERENCES LivestreamMain
                                         ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on userWatchLivestream to public;

CREATE TABLE userWatchChannel (
                                  ChannelID Varchar(10),
                                  UserID Varchar(10),
                                  PRIMARY KEY (ChannelID, UserID),
                                  FOREIGN KEY (UserID) REFERENCES ubcer
                                      ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                                  FOREIGN KEY (ChannelID) REFERENCES channel
                                      ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on userWatchChannel to public;

CREATE TABLE posted (
                        UserID Varchar(10),
                        PostID Varchar(10),
                        PRIMARY KEY (UserID, PostID),
                        FOREIGN KEY (UserID) REFERENCES ubcer
                            ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                        FOREIGN KEY (PostID) REFERENCES forum
                            ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on posted to public;

CREATE TABLE scSponsorRV (
                             VideoID Varchar(10),
                             SponsorID Varchar(10),
                             ChannelID Varchar(10),
                             PRIMARY KEY (VideoID, SponsorID, ChannelID),
                             FOREIGN KEY (SponsorID) REFERENCES sponsorCompany
                                 ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                             FOREIGN KEY (VideoID, ChannelID) REFERENCES rvContain
                                 ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on scSponsorRV to public;

CREATE TABLE SCSponsorChannel  (
                                   ChannelID Varchar(10),
                                   SponsorID Varchar(10),
                                   PRIMARY KEY(ChannelID, SponsorID),
                                   FOREIGN KEY (ChannelID) REFERENCES channel
                                       ON DELETE CASCADE,
    --ON UPDATE CASCADE,
                                   FOREIGN KEY (SponsorID) REFERENCES sponsorCompany
                                       ON DELETE CASCADE);
--ON UPDATE CASCADE);
grant select on SCSponsorChannel to public;

insert into channel
values('C-00000001', 1, 'V-00000001, V-00000002, V-00000003, V-00000004, V-00000005');
insert into channel
values('C-00000002', 0, 'V-00000001, V-00000002, V-00000003, V-00000004');
insert into channel
values('C-00000003', 0, 'V-00000001, V-00000002, V-00000003');
insert into channel
values('C-00000004', 1, 'V-00000001, V-00000002');
insert into channel
values('C-00000005', 1, 'V-00000001, V-00000006');
insert into channel
values('C-00000006', 1, 'V-00000002, V-00000003');

insert into forum
values('P-00000001', 'music', 'Taylor Swift', 'Love Story');
insert into forum
values('P-00000002', 'TV', 'Chace Gonzalez', 'Talent Show');
insert into forum
values('P-00000003', 'meal', 'Gorden Ramsay', 'Wonderful Steak');
insert into forum
values('P-00000004', 'learnning', 'Joma Tech', 'Leetcode Solution');
insert into forum
values('P-00000005', 'sport', 'Joel Levy', 'Swimming technique');
insert into forum
values('P-00000006', 'sport', 'Missi Ivy', 'Soccer technique');

insert into ubcer
values('U-00000001', 'Taylor Swift', 'C-00000001, C-00000002, C-00000003, C-00000004, C-00000005');
insert into ubcer
values('U-00000002', 'Chace Gonzalez', 'C-00000001, C-00000002, C-00000003, C-00000004');
insert into ubcer
values('U-00000003', 'Gorden Ramsay', 'C-00000001, C-00000002, C-00000003');
insert into ubcer
values('U-00000004', 'Joma Tech', 'C-00000001, C-00000002');
insert into ubcer
values('U-00000005', 'Joel Levy', 'C-00000001');

insert into vipUserData
values(3, TO_DATE('2021-10-23', 'yyyy-mm-dd'), TO_DATE('2021-10-26', 'yyyy-mm-dd'));
insert into vipUserData
values(6, TO_DATE('2021-11-23', 'yyyy-mm-dd'), TO_DATE('2021-11-29', 'yyyy-mm-dd'));
insert into vipUserData
values(9, TO_DATE('2021-12-23', 'yyyy-mm-dd'), TO_DATE('2022-01-02', 'yyyy-mm-dd'));
insert into vipUserData
values(12, TO_DATE('2022-01-23', 'yyyy-mm-dd'), TO_DATE('2021-01-30', 'yyyy-mm-dd'));
insert into vipUserData
values(1, TO_DATE('2022-02-23', 'yyyy-mm-dd'), TO_DATE('2022-02-24', 'yyyy-mm-dd'));

insert into vipUserMain
values('U-00000001', 3, TO_DATE('2021-10-23', 'yyyy-mm-dd'));
insert into vipUserMain
values('U-00000002', 6, TO_DATE('2021-11-23', 'yyyy-mm-dd'));
insert into vipUserMain
values('U-00000003', 9, TO_DATE('2021-12-23', 'yyyy-mm-dd'));
insert into vipUserMain
values('U-00000004', 12, TO_DATE('2022-01-23', 'yyyy-mm-dd'));
insert into vipUserMain
values('U-00000005', 1, TO_DATE('2022-02-23', 'yyyy-mm-dd'));

-- -- sqlplus ora_

insert into LivestreamData
values (20, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'), TO_DATE('2021-10-23 10:00:20', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamData
values (30, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'), TO_DATE('2021-10-23 10:00:30', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamData
values (40, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'), TO_DATE('2021-10-23 10:00:40', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamData
values (50, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'), TO_DATE('2021-10-23 10:00:50', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamData
values (60, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'), TO_DATE('2021-10-23 11:00:00', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamData
values (70, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'), TO_DATE('2021-10-23 11:10:00', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamData
values (62, TO_DATE('2021-10-23 11:00:00', 'yyyy-mm-dd HH:MI:SS'), TO_DATE('2021-10-23 12:02:00', 'yyyy-mm-dd HH:MI:SS'));

insert into LivestreamMain
values ('L-00000001', 'music', 20, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamMain
values ('L-00000002', 'talk show', 30, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamMain
values ('L-00000003', 'shopping', 40, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamMain
values ('L-00000004', 'sports', 50, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamMain
values ('L-00000005', 'jam', 60, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamMain
values ('L-00000006', 'jam', 70, TO_DATE('2021-10-23 10:00:00', 'yyyy-mm-dd HH:MI:SS'));
insert into LivestreamMain
values ('L-00000007', 'jam', 62, TO_DATE('2021-10-23 11:00:00', 'yyyy-mm-dd HH:MI:SS'));


insert into rvContain
values('V-00000001', 'C-00000001', '00:10:00','music', '2021-10-23 14:00:00', 1);
insert into rvContain
values('V-00000002', 'C-00000002', '00:10:00','TV', '2021-10-23 15:00:00', 0);
insert into rvContain
values('V-00000003', 'C-00000003', '00:10:00','meal', '2021-10-23 16:00:00', 1);
insert into rvContain
values('V-00000004', 'C-00000004', '00:10:00','learnning', '2021-10-23 17:00:00', 0);
insert into rvContain
values('V-00000005', 'C-00000005', '00:10:00','sport', '2021-10-23 18:00:00', 0);
insert into rvContain
values('V-00000006', 'C-00000005', '00:10:00','game', '2021-10-23 19:00:00', 1);

insert into accountOwn
values('AC-0000001', 'U-00000001', 0, 'yufeicaimail0@gmail.com', '12345678', TO_DATE('2001-08-31', 'yyyy-mm-dd'));
insert into accountOwn
values('AC-0000002', 'U-00000002', 1, 'yufeicaimail1@gmail.com', '1234567', TO_DATE('2001-09-30', 'yyyy-mm-dd'));
insert into accountOwn
values('AC-0000003', 'U-00000003', 1, 'yufeicaimail2@gmail.com', '123456', TO_DATE('2001-10-31', 'yyyy-mm-dd'));
insert into accountOwn
values('AC-0000004', 'U-00000004', 0, 'yufeicaimail3@gmail.com', '12345', TO_DATE('2001-11-30', 'yyyy-mm-dd'));
insert into accountOwn
values('AC-0000005', 'U-00000005', 0, 'yufeicaimail4@gmail.com', '1234', TO_DATE('2001-12-31', 'yyyy-mm-dd'));
insert into accountOwn
values('AC-0000006', 'U-00000005', 0, 'yufeicaimail5@gmail.com', '123', TO_DATE('2000-01-31', 'yyyy-mm-dd'));

insert into helpcenter
values ('H-00000001', 'C-00000005', 'U-00000001', 'V-00000005', 'AC-0000001', 'P-00000001', 'L-00000001', 0, 'channel content gone', 'channel-Content');
insert into helpcenter
values ('H-00000002', 'C-00000001', 'U-00000002', 'V-00000001', 'AC-0000002', 'P-00000002', 'L-00000002', 1, 'Can not create mutiple accounts', 'ubcer-Account');
insert into helpcenter
values ('H-00000003', 'C-00000003', 'U-00000003', 'V-00000003', 'AC-0000003', 'P-00000003', 'L-00000003', 0, 'Video are blocked', 'Video-Permission');
insert into helpcenter
values ('H-00000004', 'C-00000002', 'U-00000004', 'V-00000002', 'AC-0000004', 'P-00000004', 'L-00000004', 1, 'Log in failed', 'Account-LogIn');
insert into helpcenter
values ('H-00000005', 'C-00000001', 'U-00000005', 'V-00000001', 'AC-0000005', 'P-00000004', 'L-00000005', 1, 'racially discriminatory posted', 'posted-AgressContent');
insert into helpcenter
values ('H-00000006', 'C-00000001', 'U-00000005', 'V-00000001', 'AC-0000006', 'P-00000006', 'L-00000003', 0, 'Livestream cannot be recorded', 'Livestream-Record');

insert into adminiStatus
values('Valdi Gigges', 1);
insert into adminiStatus
values('Martin Potter', 0);
insert into adminiStatus
values('Angelina Watson', 1);
insert into adminiStatus
values('Chris Pang', 1);
insert into adminiStatus
values('David Hoffman', 0);
insert into adminiStatus
values('Eliza Gates', 1);
insert into adminiStatus
values('Sanai Banks', 1);

insert into adminiMain
values('AD-0000001','Valdi Gigges');
insert into adminiMain
values('AD-0000002','Martin Potter');
insert into adminiMain
values('AD-0000003','Angelina Watson');
insert into adminiMain
values('AD-0000004','Chris Pang');
insert into adminiMain
values('AD-0000005','David Hoffman');
insert into adminiMain
values('AD-0000006','Eliza Gates');
insert into adminiMain
values('AD-0000007','Sanai Banks');

insert into sponsorCompany
values('S-00000001','Addidas',10000);
insert into sponsorCompany
values('S-00000002','Nike', 10000);
insert into sponsorCompany
values('S-00000003','Blue Navy', null);
insert into sponsorCompany
values('S-00000004','Puma', 2000);
insert into sponsorCompany
values('S-00000005','Pop Mart', 1);
insert into sponsorCompany
values('S-00000006','Pop Mart', 100);

-- all accounts are monitored by ads
insert into adminiMonitorAccount
values('AD-0000001','AC-0000001','U-00000001');
-- one ad monitored several accounts
insert into adminiMonitorAccount
values('AD-0000001','AC-0000006','U-00000005');
-- many ads to one account
insert into adminiMonitorAccount
values('AD-0000002','AC-0000001','U-00000001');
insert into adminiMonitorAccount
values('AD-0000003','AC-0000002','U-00000002');
insert into adminiMonitorAccount
values('AD-0000004','AC-0000003','U-00000003');
insert into adminiMonitorAccount
values('AD-0000006','AC-0000004','U-00000004');
-- one user has multiple accounts
insert into adminiMonitorAccount
values('AD-0000007','AC-0000005','U-00000005');

-- all posts are monitored by ads
-- many ads to one forum
insert into adminiMonitorForum
values('AD-0000001','P-00000001');
-- one ad monitored several forums
insert into adminiMonitorForum
values('AD-0000002','P-00000001');
insert into adminiMonitorForum
values('AD-0000002','P-00000002');
insert into adminiMonitorForum
values('AD-0000003','P-00000003');
insert into adminiMonitorForum
values('AD-0000004','P-00000004');
insert into adminiMonitorForum
values('AD-0000005','P-00000005');
insert into adminiMonitorForum
values('AD-0000003','P-00000006');

-- all livestreams are monitored by ads
-- many ads to one livestream
insert into adminiMonitorLivestream
values('AD-0000001','L-00000001');
-- one ad monitored several livestreams
insert into adminiMonitorLivestream
values('AD-0000002','L-00000001');
insert into adminiMonitorLivestream
values('AD-0000002','L-00000002');
insert into adminiMonitorLivestream
values('AD-0000003','L-00000003');
insert into adminiMonitorLivestream
values('AD-0000004','L-00000004');
insert into adminiMonitorLivestream
values('AD-0000005','L-00000005');

-- all videos are monitored by ads
-- many ads to one video
insert into adminiApproveRV
values('AD-0000001','V-00000001','C-00000001');
insert into adminiApproveRV
values('AD-0000007','V-00000001','C-00000001');
insert into adminiApproveRV
values('AD-0000002','V-00000002','C-00000002');
insert into adminiApproveRV
values('AD-0000003','V-00000003','C-00000003');
insert into adminiApproveRV
values('AD-0000004','V-00000004','C-00000004');
insert into adminiApproveRV
values('AD-0000005','V-00000005','C-00000005');
-- one ad monitored several videos
-- one channel has multiple videos
insert into adminiApproveRV
values('AD-0000001','V-00000006','C-00000005');

-- all livestream are produced by users
-- one user can produce several livestreams
-- not all users produce livestreams
insert into userProduceLivestream
values('L-00000001','U-00000001');
insert into userProduceLivestream
values('L-00000002','U-00000001');
insert into userProduceLivestream
values('L-00000003','U-00000003');
insert into userProduceLivestream
-- one livestream can be produced by several users
values('L-00000004','U-00000005');
insert into userProduceLivestream
values('L-00000004','U-00000001');
insert into userProduceLivestream
values('L-00000004','U-00000002');
insert into userProduceLivestream
values('L-00000005','U-00000005');

-- all channels are produced by users
-- one user can produce several channels
-- not all users produce channels
insert into userProduceChannel
values('C-00000001','U-00000001');
insert into userProduceChannel
values('C-00000002','U-00000001');
insert into userProduceChannel
values('C-00000003','U-00000003');
insert into userProduceChannel
-- one channel can be produced by several users
values('C-00000004','U-00000005');
insert into userProduceChannel
values('C-00000004','U-00000001');
insert into userProduceChannel
values('C-00000004','U-00000002');
insert into userProduceChannel
values('C-00000005','U-00000005');

-- a user can watch several livestreams
insert into userWatchLivestream
values('L-00000002','U-00000001');
insert into userWatchLivestream
values('L-00000003','U-00000001');
-- a livestream can be watched by several users
insert into userWatchLivestream
values('L-00000001','U-00000002');
insert into userWatchLivestream
values('L-00000001','U-00000001');
insert into userWatchLivestream
values('L-00000001','U-00000005');
insert into userWatchLivestream
values('L-00000005','U-00000005');

-- a user can watch several channels
insert into userWatchChannel
values('C-00000001','U-00000001');
insert into userWatchChannel
values('C-00000005','U-00000001');
-- a channel can be watched by several users
insert into userWatchChannel
values('C-00000003','U-00000002');
insert into userWatchChannel
values('C-00000003','U-00000001');
insert into userWatchChannel
values('C-00000003','U-00000005');
insert into userWatchChannel
values('C-00000002','U-00000005');

-- a posted can be posted by several users
insert into posted
values('U-00000001','P-00000001');
insert into posted
values('U-00000005','P-00000001');
-- a user can posted several posts
insert into posted
values('U-00000001','P-00000002');
insert into posted
values('U-00000003','P-00000003');
insert into posted
values('U-00000002','P-00000004');
insert into posted
values('U-00000004','P-00000005');
insert into posted
values('U-00000001','P-00000006');

-- a sponsor company can sponsor many videos
insert into scSponsorRV
values('V-00000001','S-00000005', 'C-00000001');
insert into scSponsorRV
values('V-00000003','S-00000005', 'C-00000003');
-- a video can be sponsored by many sponsor companies
insert into scSponsorRV
values('V-00000004','S-00000005', 'C-00000004');
insert into scSponsorRV
values('V-00000004','S-00000001', 'C-00000004');
insert into scSponsorRV
values('V-00000004','S-00000002', 'C-00000004');


-- a sponsor company can sponsor many channels
insert into SCSponsorChannel
values('C-00000001','S-00000005');
insert into SCSponsorChannel
values('C-00000003','S-00000006');
insert into SCSponsorChannel
values('C-00000002','S-00000004');
-- a channel can be sponsored by many sponsor companies
insert into SCSponsorChannel
values('C-00000004','S-00000005');
insert into SCSponsorChannel
values('C-00000004','S-00000001');
insert into SCSponsorChannel
values('C-00000004','S-00000003');
insert into SCSponsorChannel
values('C-00000004','S-00000002');
insert into SCSponsorChannel
values('C-00000004','S-00000004');
insert into SCSponsorChannel
values('C-00000004','S-00000006');

COMMIT WORK;

