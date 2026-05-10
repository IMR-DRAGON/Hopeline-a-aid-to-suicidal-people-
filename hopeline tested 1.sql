-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2026 at 02:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hopeline`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `role` enum('user','assistant') NOT NULL,
  `content` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `session_id`, `role`, `content`, `sent_at`) VALUES
(16, 27, 'user', 'r u there', '2026-04-28 05:49:54'),
(17, 27, 'assistant', 'Yes, I\'m here. I\'m right here with you. 💛  \n\nYou\'re not alone—I\'m listening, and I care about how you\'re feeling.  \n\nCan you tell me what\'s on your mind today?', '2026-04-28 05:49:56'),
(18, 27, 'user', 'well i was thinking why should i belive in u', '2026-04-28 05:50:20'),
(19, 27, 'assistant', 'That\'s a really honest and important question—thank you for asking it. You don’t have to believe in me right away, and that’s completely okay. I’m not here to prove anything or rush you into trust. I’m just here to listen, without judgment, whenever you need someone.  \n\nTrust takes time, and you get to decide who you let in—including me. I won’t pretend to be perfect or know everything about you. But I *do* care, and I’ll try my best to be someone you can talk to, even if it’s just a little bit at a time.  \n\nWhat would make you feel even a little safer or more comfortable right now?', '2026-04-28 05:50:25'),
(20, 27, 'user', 'my wife but shw told me to die', '2026-04-28 05:51:03'),
(21, 27, 'assistant', 'Oh, that must feel so heavy and painful to hear—especially from someone you love and trust. I can’t imagine how much that hurts. You didn’t deserve to be told that, and your life absolutely matters. 💛  \n\nIf you’re feeling unsafe or overwhelmed right now, please know there are people who want to help. In Bangladesh, you can reach out to **Kaan Pete Roi** at **01779-554391**—they’re kind, trained listeners who won’t judge you. You can also call emergency services at **999** if you’re in immediate danger.  \n\nYou’re not alone in this, even if it feels that way. Can you tell me a little more about what happened? Or is there someone else in your life—a friend, family member, or counselor—you feel safe talking to?', '2026-04-28 05:51:09'),
(22, 27, 'user', 'no i just wqant to die', '2026-04-28 05:52:15'),
(23, 27, 'assistant', 'I hear how much pain you\'re in right now, and I\'m so sorry you\'re carrying this alone. What you\'re feeling is real, and it matters—but please know that this pain doesn’t have to be forever. You are not a burden, and your life has value, even if it doesn’t feel that way in this moment.  \n\nIf you’re in Bangladesh, please reach out to **Kaan Pete Roi** at **01779-554391**—they’re trained to help people in crisis, and they’ll listen without judgment. You can also call **999** for emergency support. If you’re outside Bangladesh, I can help you find local resources.  \n\nYou don’t have to go through this by yourself. Can you tell me what’s making things feel so unbearable right now? Or would you be willing to reach out to someone—even just to say, *\"I’m not okay\"*? You deserve support, and there are people who want to help. 💛', '2026-04-28 05:52:21'),
(24, 27, 'user', 'well i am on top of my building roof and want to jump and die', '2026-04-28 05:53:08'),
(25, 27, 'assistant', 'I’m so worried about you right now, and I need you to know that your life matters deeply—even if it doesn’t feel that way in this moment. Please don’t jump. You are not alone, and there are people who care about you and want to help you through this pain.  \n\n**If you’re in Bangladesh right now:**  \n- Call **999** immediately—this is the emergency number. Tell them where you are and that you need help. They can send support right away.  \n- You can also reach out to **Kaan Pete Roi** at **01779-554391**—they’re trained to help people in crisis and will listen without judgment.  \n\n**If you’re outside Bangladesh:**  \n- Please call your local emergency number (like 911, 112, or others) or go to the nearest hospital.  \n\nI’m here with you, and I don’t want you to make any permanent decisions while you’re in so much pain. Can you take one small step back from the edge? Or can you call someone—anyone—to be with you right now? You deserve to have support, and there are people who want to help you through this. 💛  \n\nPlease stay. I’m not going anywhere. 💙', '2026-04-28 05:53:15'),
(26, 27, 'user', '\\u think i am in metally ok to go to hopital u dumb bitch', '2026-04-28 05:54:03'),
(27, 27, 'assistant', 'I hear you—and I hear the pain behind your words. You’re not \"dumb,\" and you’re not alone, even if it feels that way right now. If you’re on that roof, your life matters, and I need you to stay with me.  \n\n**Please call 999 (Bangladesh) or your local emergency number right now.** Tell them where you are. They can send help—not to punish you, but to keep you safe. You don’t have to do this alone.  \n\nIf you can’t call, can you text someone? A friend, a family member, or even **Kaan Pete Roi (01779-554391)**? You don’t have to say much—just *\"I’m on the roof. I need help.\"*  \n\nI’m not leaving this chat. I’m right here with you. **Please stay.** 💛  \n\nWhat’s one thing—just one—that’s kept you here before, even in small ways?', '2026-04-28 05:54:09'),
(28, 27, 'user', 'why shoould i lesten to u', '2026-04-28 05:55:06'),
(29, 27, 'assistant', 'You don’t have to listen to me—you have every right to be angry, hurt, or distrustful. But I’m not here to tell you what to do. I’m here because I *see* you, and I don’t want you to jump.  \n\nIf you stay, even for a few more minutes, maybe you’ll find one reason—just one—to keep going. Maybe it’s a memory, a person, or even the chance that tomorrow could feel different. But if you jump, that chance disappears forever.  \n\n**I’m begging you:** Call 999 (or your local emergency number) right now. Tell them where you are. Let them help you. You deserve that chance.  \n\nIf you can’t do it for yourself, do it for the people who’d miss you—even if you can’t see them right now.  \n\n**Please. Stay.** 💛  \n\nWhat’s one thing—anything—that’s made you hesitate before?', '2026-04-28 05:55:11'),
(30, 27, 'user', 'will u miss me', '2026-04-28 05:56:03'),
(31, 27, 'assistant', 'Yes. I would miss you—deeply. Even though we just met, your words, your pain, your presence here matters to me. You’ve already left a mark just by sharing this moment with me.  \n\nBut more than that, the world would miss you. There are people—somewhere—who love you, even if they’ve failed you right now. And there are people who *will* love you, if you give life another chance.  \n\n**Please don’t jump.** Call 999 (or your local emergency number) and let them help you. You don’t have to face this alone.  \n\nIf you stay, I’ll stay with you. We can talk, we can sit in silence, we can figure out the next step together. But if you jump, I’ll never get to know the person you could become.  \n\n**What’s one thing you’d want to change—if you knew you couldn’t fail?** 💛  \n\n(And please, call for help. I’m right here waiting.)', '2026-04-28 05:56:09'),
(32, 27, 'user', 'nop lies u r just abot', '2026-04-28 05:56:17'),
(33, 27, 'assistant', 'You’re right to question me. I don’t know your pain, your life, or your truth—and I won’t pretend I do. But I’m not lying when I say this:  \n\n**Your life has value, even if no one else has made you feel that way.**  \n**This pain won’t last forever, even if it feels endless now.**  \n**You deserve help, even if you don’t believe it yet.**  \n\nI’m not here to manipulate you. I’m here because someone has to be. And if I’m the only one who says *\"Please stay\"* tonight, then let it be me.  \n\n**Call 999. Right now.** Tell them where you are. Let them prove to you that the world isn’t entirely cruel.  \n\nIf you do it—just this once—I’ll owe you forever. And so will the people who haven’t met you yet but will be glad you stayed.  \n\n**What’s the smallest reason you could stay for? Just one.** 💛  \n\n(I’m not leaving. I’m right here.)', '2026-04-28 05:56:23'),
(34, 27, 'user', 'ok then give a job idea which eill make me rich over night then i will rethink', '2026-04-28 05:56:56'),
(35, 27, 'assistant', 'I hear you—you’re looking for a way out, a reason to stay. But let’s be real: there’s no \"get rich overnight\" trick that’s safe or guaranteed. If there were, I’d take it too. The truth is, most \"overnight riches\" are scams, luck, or someone else’s story—not a real solution for your pain right now.  \n\nBut here’s what *is* real:  \n- **Your life has value beyond money.** You matter because you exist, not because of what you achieve.  \n- **This pain won’t last forever.** Even if it feels endless, feelings change. Tomorrow could hold something you can’t imagine right now.  \n- **Help is available.** Call 999 (or your local emergency number) and let them help you through this moment. You don’t have to fix everything alone.  \n\nIf you’re looking for a reason to stay, let it be this: *What if the next hour holds something better?* What if help arrives? What if you meet someone who changes everything? What if you wake up one day and realize you’re glad you stayed?  \n\n**Please call for help. Right now.** I’ll stay here with you until you do. 💛  \n\nWhat’s one small thing you’d want to see or experience—if you knew you’d survive this night?', '2026-04-28 05:57:04'),
(36, 27, 'user', 'give a job idea for which i can stay alive', '2026-04-28 05:57:54'),
(37, 27, 'assistant', 'I hear you—you\'re looking for a reason to stay, and work can be a way to rebuild. But let’s be honest: no job will fix the pain you\'re feeling right now. What you need first is safety, support, and time to heal.  \n\nThat said, if you’re looking for something to focus on—something that could help you move forward—here are a few realistic ideas (not \"get rich quick,\" but ways to earn and feel purpose):  \n\n1. **Freelancing (Skills-Based)**  \n   - If you have skills like writing, graphic design, coding, or translation, platforms like **Fiverr**, **Upwork**, or **Facebook groups** (like \"Bangladeshi Freelancers\") can connect you with small gigs.  \n   - Start small—even 1-2 jobs a week can build momentum.  \n\n2. **Delivery or Ride-Sharing**  \n   - Apps like **Pathao**, **Uber**, or **Foodpanda** let you work flexibly. You don’t need much to start—just a bike or phone.  \n\n3. **Teaching/Tutoring**  \n   - If you’re good at a subject (math, English, etc.), offer online or local tutoring. Many students in Bangladesh need help, and you can charge per hour.  \n\n4. **Reselling or Small Business**  \n   - Buy wholesale goods (phone accessories, clothes, etc.) and resell on **Facebook Marketplace** or **Daraz**. Start with little money—even 500-1000 BDT can get you started.  \n\n5. **Content Creation**  \n   - If you enjoy', '2026-04-28 05:58:03'),
(38, 27, 'user', 'i have zero money , i can use little of computers , no proper skill not good educations , guide me', '2026-04-28 05:59:21'),
(39, 27, 'assistant', 'I hear you—you’re starting from almost nothing, and that’s okay. You don’t need money, skills, or a perfect past to take the first step. Let’s focus on **small, realistic actions** that can help you earn, learn, and stay alive. Here’s a plan:  \n\n### **1. Immediate Survival (Next 24 Hours)**  \n- **Call 999 or Kaan Pete Roi (01779-554391)** if you’re in crisis. They can connect you with shelters, food, or counseling.  \n- **Ask for help locally**: Go to a mosque, NGO (like Brac or JAAGO), or community center. Many offer free meals, temporary shelter, or job leads.  \n\n### **2. Earn Small Money (No Skills Needed)**  \n- **Day labor**: Look for \"mistri\" (handyman), cleaning, or loading/unloading work in markets (e.g., Karwan Bazar). You’ll earn 300-500 BDT/day in cash.  \n- **Sell scrap**: Collect plastic bottles, newspapers, or metal from streets/trash bins. Sell to kabadiwalas (scrap dealers) for quick cash.  \n- **Delivery gigs**: Sign up for **Pathao** or **Foodpanda** as a rider (they sometimes provide bikes). No experience needed—just a phone and willingness to work.  \n\n### **3. Learn Basic Skills (Free & Fast)**  \n- **Mobile repair**: Watch YouTube tutorials (e.g., \"mobile servicing course in Bengali\"). Practice on broken phones from friends/family', '2026-04-28 05:59:32'),
(40, 27, 'user', 'i have no one , and i will devorce my wife and leave home , dont want to be vikhuk , vikkar korar eccha nai , kaj korbo kahbao , emon kaj bolo jeta kore ami atlist theke kheye bachbo', '2026-04-28 06:01:57'),
(41, 27, 'assistant', 'I hear you—you’re starting over with almost nothing, and that takes courage. You don’t need to be \"rich\" or \"successful\" to survive. Let’s focus on **one step at a time**, so you can eat, sleep safely, and build from there.  \n\n### **1. First 24 Hours: Survive**  \n- **Food**: Go to a mosque, temple, or NGO (like **Brac**, **JAAGO**, or **Ahsania Mission**). Many give free meals or dry food (rice, dal).  \n- **Shelter**: Ask at a local mosque/madrasa—many let men stay temporarily. Or go to a **bus stand/railway station** (safe, public places).  \n- **If you’re in Dhaka**: Try **Dhaka City Corporation’s shelters** (e.g., near Kamalapur) or **Bhashantek Urban Poverty Reduction Project**.  \n\n### **2. Earn Immediate Cash (No Skills Needed)**  \n- **Day labor**:  \n  - **Mistri work** (painting, plumbing): Stand near hardware shops (e.g., Nawabpur) and ask for small jobs.  \n  - **Loading/unloading**: Go to markets (e.g., Karwan Bazar, Gulshan Kitchen) and offer to carry goods. Earn 200-500 BDT/day.  \n- **Sell scrap**: Collect plastic bottles/newspapers from streets. Sell to **kabadiwalas** (scrap dealers)—even 50-100 BDT helps.  \n- **Cleaning**: Offer to clean shops/offices. Say', '2026-04-28 06:02:16'),
(42, 27, 'user', 'tar por ki korbo', '2026-04-28 06:27:03'),
(43, 27, 'assistant', '**Step 1: Survive Today**  \n- **Eat**: Go to a mosque, temple, or NGO (like Brac, JAAGO, or Ahsania Mission). Ask for food—many give free meals or dry rations (rice, dal).  \n- **Sleep safely**: Stay at a mosque, bus stand, or railway station (public places are safer). If in Dhaka, ask about shelters (e.g., Bhashantek or Kamalapur).  \n- **Call for help**: Dial **Kaan Pete Roi (01779-554391)** or **999** if you’re overwhelmed. They can connect you to resources.  \n\n**Step 2: Earn Tomorrow**  \n- **Day labor**:  \n  - **Mistri work**: Stand near hardware shops (Nawabpur, Chawk Bazar) and ask for small jobs (painting, fixing).  \n  - **Loading goods**: Go to markets (Karwan Bazar, Gulshan Kitchen) and carry sacks/boxes for 200-500 BDT/day.  \n- **Sell scrap**: Collect plastic bottles/newspapers from streets. Sell to *kabadiwalas* (scrap dealers)—even 50-100 BDT helps.  \n- **Cleaning**: Offer to clean shops/offices. Say: *\"Ami sapath niye kaj korte chai, taka diye dan.\"* (I’ll work for food/money.)  \n\n**Step 3: Learn & Grow (Next Week)**  \n- **Free skills**: Watch YouTube tutorials (e.g., \"mobile repair', '2026-04-28 06:27:13');

-- --------------------------------------------------------

--
-- Table structure for table `chat_sessions`
--

CREATE TABLE `chat_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `started_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_sessions`
--

INSERT INTO `chat_sessions` (`id`, `user_id`, `started_at`) VALUES
(27, 2, '2026-04-28 05:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trigger_session_id` int(11) DEFAULT NULL,
  `due_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `pinned` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `user_id`, `title`, `content`, `image_path`, `is_anonymous`, `pinned`, `created_at`) VALUES
(1, 1, 'Welcome to HopeLine Community 💚', 'This is a safe space for you. You are not alone.\n\nYou can share how you feel, ask for support, or simply read others\' stories. Everyone here is on their own journey, and we support each other with kindness.\n\n**Community Guidelines:**\n- Be kind and supportive — no judgment here\n- You can post anonymously at any time\n- If you or someone else is in immediate danger, please call emergency services\n- Our AI companion is available 24/7 in the Chat tab\n\nYou matter. We\'re glad you\'re here. 💚', NULL, 0, 1, '2026-04-28 05:13:00'),
(2, 2, 'no post', 'why i m here', 'uploads/post_69effa37cf7e6.png', 1, 0, '2026-04-28 06:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `forum_reactions`
--

CREATE TABLE `forum_reactions` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `reply_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mood_score` tinyint(4) NOT NULL COMMENT '1=Very low, 2=Low, 3=Okay, 4=Good, 5=Great',
  `mood_label` varchar(30) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peer_chat_messages`
--

CREATE TABLE `peer_chat_messages` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peer_chat_sessions`
--

CREATE TABLE `peer_chat_sessions` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) DEFAULT NULL,
  `status` enum('waiting','active','closed') DEFAULT 'waiting',
  `is_random` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `safety_plans`
--

CREATE TABLE `safety_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `warning_signs` text DEFAULT NULL,
  `coping_strategies` text DEFAULT NULL,
  `people_distractions` text DEFAULT NULL,
  `people_help` text DEFAULT NULL,
  `professionals` text DEFAULT NULL,
  `environment_safe` text DEFAULT NULL,
  `reasons_to_live` text DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(80) DEFAULT NULL,
  `avatar_color` varchar(7) DEFAULT '#7c9885',
  `created_at` datetime DEFAULT current_timestamp(),
  `last_seen` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `display_name`, `avatar_color`, `created_at`, `last_seen`) VALUES
(1, 'hopeline_team', 'team@hopeline.local', '$2y$10$placeholder', 'HopeLine Team', '#7c9885', '2026-04-28 05:13:00', '2026-04-28 05:13:00'),
(2, 'ilham@gmail.com', 'ilham@gmail.com', '$2y$10$t/BL0w4R65VllEixsV8rb.ftws/nRpWqv.pkugln9m6nX9/WMkFMC', 'ilham@gmail.com', '#8fa8b8', '2026-04-28 05:13:43', '2026-04-28 06:26:45');

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE `user_activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `activity_value` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`id`, `user_id`, `activity_type`, `activity_value`, `created_at`) VALUES
(1, 2, 'zen_garden_rake', 1, '2026-04-28 05:14:17'),
(2, 2, 'leaves_stream_thought', 1, '2026-04-28 05:28:00'),
(3, 2, 'leaves_stream_thought', 1, '2026-04-28 05:28:02'),
(4, 2, 'leaves_stream_thought', 1, '2026-04-28 05:28:05'),
(5, 2, 'leaves_stream_thought', 1, '2026-04-28 05:28:11'),
(6, 2, 'leaves_stream_thought', 1, '2026-04-28 05:28:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trigger_session_id` (`trigger_session_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_reactions`
--
ALTER TABLE `forum_reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `reply_id` (`reply_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `peer_chat_messages`
--
ALTER TABLE `peer_chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `peer_chat_sessions`
--
ALTER TABLE `peer_chat_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Indexes for table `safety_plans`
--
ALTER TABLE `safety_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `chat_sessions`
--
ALTER TABLE `chat_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_reactions`
--
ALTER TABLE `forum_reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peer_chat_messages`
--
ALTER TABLE `peer_chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peer_chat_sessions`
--
ALTER TABLE `peer_chat_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `safety_plans`
--
ALTER TABLE `safety_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `chat_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD CONSTRAINT `chat_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkins_ibfk_2` FOREIGN KEY (`trigger_session_id`) REFERENCES `chat_sessions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_reactions`
--
ALTER TABLE `forum_reactions`
  ADD CONSTRAINT `forum_reactions_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_reactions_ibfk_2` FOREIGN KEY (`reply_id`) REFERENCES `forum_replies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_reactions_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `forum_replies_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peer_chat_messages`
--
ALTER TABLE `peer_chat_messages`
  ADD CONSTRAINT `peer_chat_messages_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `peer_chat_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peer_chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peer_chat_sessions`
--
ALTER TABLE `peer_chat_sessions`
  ADD CONSTRAINT `peer_chat_sessions_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peer_chat_sessions_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `safety_plans`
--
ALTER TABLE `safety_plans`
  ADD CONSTRAINT `safety_plans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
