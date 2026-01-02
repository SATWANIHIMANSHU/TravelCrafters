
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
-- TravelCrafters Database
-- This SQL file contains ONLY demo/sample data

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travelcrafters`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.jpg',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(15) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'Administrator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `email`, `profile_pic`, `password`, `created_at`, `phone`, `role`) VALUES
(1, 'admin', 'admin@example.com', 'default.jpg', '$2y$10$abcdefghijklmnopqrstuv', CURRENT_TIMESTAMP, NULL, 'Administrator');
-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `travel_date` date NOT NULL,
  `adults` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `package_id`, `user_name`, `email`, `phone`, `travel_date`, `adults`, `children`, `total_amount`, `status`, `created_at`, `user_id`) VALUES
(1, 1, 'Demo User', 'demo1@example.com', '9999999999', '2025-06-01', 2, 1, 50000.00, 'completed', CURRENT_TIMESTAMP, 1),
(2, 2, 'Test User', 'demo2@example.com', '8888888888', '2025-06-10', 1, 0, 30000.00, 'pending', CURRENT_TIMESTAMP, 2);


-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Himanshu Maheshkumar Satwani', 'satwanihimanshu35@gmail.com', 'Inquiry about Tour Packages', 'I want to know more about your travel packages to Bali. Please share details.', '2025-04-03 07:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_type` enum('card','grid','image_grid') NOT NULL DEFAULT 'card'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `description`, `image`, `display_type`) VALUES
(1, 'Rajasthan', 'Rajasthan, the land of kings, is famous for its magnificent forts, palaces, and desert landscapes.', 'rajasthan.jpg', 'card'),
(2, 'Malaysia', 'Malaysia offers a mix of modern cities, beautiful beaches, and diverse cultural heritage.', 'destination-1.jpg', 'card'),
(3, 'Singapore', 'Australia is known for its stunning coastlines, wildlife, and iconic landmarks like the Sydney Opera House.', '../uploads/1745241526_singapore.png', 'card'),
(4, 'Indonesia', 'Indonesia is home to beautiful islands, beaches, volcanoes, and rich cultural traditions.', 'indonesia.jpg', 'card'),
(8, 'Bali', 'Enjoy tropical paradise in Bali with amazing resorts.', 'Maldiv2.jpg', 'grid'),
(9, 'Dubai', 'Experience luxury, skyscrapers, and desert safaris in Dubai.', 'maldivs.jpg', 'grid'),
(10, 'Switzerland', 'Witness the breathtaking Alps and scenic beauty of Switzerland.', 'cambodia.webp', 'grid'),
(11, 'Goa', 'Beautiful beaches, nightlife, and Portuguese heritage.', 'goa.jpg', 'image_grid'),
(12, 'Manali', 'Scenic hill station with snow-capped mountains and adventure sports.', 'manali.jpg', 'image_grid'),
(13, 'Shimla', 'A charming colonial-era town with stunning landscapes.', 'shimla.jpg', 'image_grid'),
(14, 'Jaipur', 'The Pink City known for palaces, forts, and rich culture.', 'jaipur.webp', 'image_grid'),
(15, 'Darjeeling', 'Famous for its tea gardens and breathtaking Himalayan views.', 'darjeeling.jpg', 'image_grid'),
(16, 'Ooty', 'A serene hill station with lush green landscapes and pleasant weather.', 'ooty.jpg', 'image_grid'),
(17, 'Mysore', 'Known for its royal heritage, Mysore Palace, and cultural beauty.', 'mysore.jpg', 'image_grid'),
(18, 'Rishikesh', 'Yoga capital of the world, known for adventure sports and spirituality.', 'rishikesh.jpg', 'image_grid'),
(19, 'Leh Ladakh', 'A paradise for bikers with stunning valleys and monasteries.', 'ladakh.jpg', 'image_grid'),
(20, 'Kanyakumari', 'Southernmost tip of India, famous for its sunrise and temples.', 'kanyakumari.jpg', 'image_grid'),
(21, 'Varanasi', 'Ancient city on the banks of the Ganges, known for spirituality.', 'varanasi.jpg', 'image_grid'),
(22, 'Amritsar', 'Home to the Golden Temple and rich Sikh heritage.', 'amritsar.jpg', 'image_grid'),
(23, 'Shillong', 'The Scotland of the East, surrounded by green hills and waterfalls.', 'shillong.jpg', 'image_grid'),
(24, 'Munnar', 'A beautiful tea plantation town in Kerala with rolling green hills.', 'munnar.jpg', 'image_grid'),
(25, 'Hampi', 'A UNESCO World Heritage site with stunning ancient ruins.', 'hampi.jpg', 'image_grid');

-- --------------------------------------------------------

--
-- Table structure for table `exclusions`
--

CREATE TABLE `exclusions` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exclusions`
--

INSERT INTO `exclusions` (`id`, `package_id`, `description`) VALUES
(1, 1, 'Airfare buses rail and transfers is not included'),
(2, 1, 'Personal expenses like tips, laundry'),
(18, 8, '5% GST'),
(20, 20, 'International / Domestic flight'),
(21, 21, 'Cost for add on services, optional tours, Guide services, Sightseeing entrance fees & Porters'),
(22, 21, 'Cost for anything which is not mentioned under “COST INCLUDES” Coolum.'),
(23, 22, 'Any Airfare'),
(24, 22, '5 % GST'),
(25, 22, 'Any Kind of Personal Expenses or Optional Tours / Extra Meals Ordered'),
(26, 22, 'The Services of Vehicle is not included on leisure days & after finishing the sightseeing tour as per the Itinerary'),
(27, 22, 'Any changes you may choose to make during your tour'),
(28, 23, '5 % GST'),
(29, 23, 'Any expenses for optional activities.'),
(30, 23, 'Items of personal nature like portage, tips, laundry'),
(31, 23, 'Any expenses arising out of unforeseen circumstances like flight delay / cancellation / hike in fare, strike or any other natural calamities or any emergency evacuation expenses'),
(32, 23, 'Anything not mentioned in Inclusions'),
(33, 24, 'Domestic / International Flight tickets'),
(34, 24, 'GST and TCS, if applicable'),
(35, 24, 'Laundry Charges'),
(36, 24, 'Meals not mentioned in above Itinerary'),
(37, 24, 'Additional Transfers or Sightseeing not mentioned in itinerary'),
(38, 24, 'TIPS / Porter Charges / Room service'),
(39, 25, 'Visa ( If applicable)'),
(40, 25, 'Travel Insurance'),
(41, 25, 'Anything not mentioned in ‘Package Inclusions’'),
(42, 25, 'All personal expenses, optional tours and extra meals not mentioned in inclusions'),
(43, 25, 'Tips, porterage, laundry and phone calls');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `rating`, `message`, `created_at`) VALUES
(1, 'Virat Kohli', 'vk885@gmail.com', 5, 'The booking process was smooth, but we faced a slight delay in transportation', '2025-03-28 13:03:35'),
(2, 'Rohit sharma', 'rsharma77@gmail.com', 2, 'The booking process was seamless, and the customer service was top-notch. ', '2025-03-28 13:20:09'),
(3, 'Himanshu Maheshkumar Satwani', 'satwanihimanshu35@gmail.com', 4, 'Very Good Service', '2025-03-28 17:34:45'),
(4, 'Virat Kohli', 'sanjaygosh@gmail.com', 5, 'Good Service', '2025-04-01 07:12:30'),
(5, 'Haresh Tanwani', 'Haresh@gmail.com', 5, 'Best Services are offered', '2025-04-01 07:41:13'),
(6, 'kapil tanwani', 'kp23@gmail.com', 5, 'Best packages and offers', '2025-04-02 06:52:49'),
(7, 'Hitesh khanschandani', 'hk45@gmail.com', 4, 'Best packages', '2025-04-03 07:43:15'),
(8, 'Virat Kohli', 'satwanihimanshu35@gmail.com', 5, 'asssxax', '2025-04-03 08:05:01'),
(9, 'Virat Kohli', 'satwanihimanshu35@gmail.com', 5, 'aassasas', '2025-04-03 08:54:35'),
(10, 'Virat Kohli', 'satwanihimanshu35@gmail.com', 3, 'acascdcdc', '2025-04-05 14:20:01'),
(11, 'kapil tanwani', 'kp23@gmail.com', 5, 'tqdfdsgfdzhthtrhfx', '2025-04-07 05:24:34'),
(12, 'Virat Kohli', 'satwanihimanshu35@gmail.com', 5, 'hrnngnfgnf', '2025-04-09 06:32:43'),
(13, 'Virat Kohli', 'satwanihimanshu35@gmail.com', 5, 'cscsccdsc', '2025-04-09 06:42:58'),
(14, 'Himanshu Maheshkumar Satwani', 'satwanihimanshu35@gmail.com', 3, 'Very good service', '2025-04-23 17:48:07'),
(15, 'Himanshu Maheshkumar Satwani', 'satwanihimanshu35@gmail.com', 4, 'very good', '2025-04-26 17:39:52');

-- --------------------------------------------------------

--
-- Table structure for table `inclusions`
--

CREATE TABLE `inclusions` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inclusions`
--

INSERT INTO `inclusions` (`id`, `package_id`, `description`) VALUES
(1, 1, 'Personal expenses such as laundry, telephone calls, room service, alcoholic beverages, mini bar etc., are included.'),
(3, 1, 'Sightseeing by private vehicle'),
(4, 1, 'Airport transfers'),
(5, 1, 'Travel insurance'),
(18, 8, 'Accommodation at Hotel.'),
(20, 20, '3 nights Accommodation at Tokyo in Mercure Tokyo Haneda Airport or La Vista Tokyo Bay or JAL City Hotel Toyosu or Similar'),
(21, 21, '03 Night Accommodation at Gangtok'),
(22, 21, 'All pick up drop & sightseeing'),
(23, 22, 'Accommodation in mentioned base category rooms'),
(24, 22, 'Daily buffet breakfast & Dinner at all the hotels'),
(25, 22, 'All sightseeing and excursions as per the itinerary'),
(26, 22, 'All transfers, city tours and transport services by Ac Car'),
(27, 22, 'Road taxes, parking fee, fuel charges'),
(28, 22, 'All applicable taxes'),
(29, 23, 'Accommodation on twin sharing basis.'),
(30, 23, 'Breakfast and Dinner at Hotel.'),
(31, 23, 'A. C. car for entire trip as per itinerary'),
(32, 23, 'Toll taxes, parking, driver charges etc.'),
(33, 23, 'All applicable hotel taxes.'),
(34, 24, '3 nights’ accommodation in given hotel or similar'),
(35, 24, 'Daily breakfast'),
(36, 24, 'Dhow Cruise Marina with International Buffet'),
(37, 24, 'Standard Desert Safari with Dinner and Belly Dance'),
(38, 24, 'Tours will be on shared basis'),
(39, 24, 'Airport transfers will be on private'),
(40, 24, 'Dubai City Tour'),
(41, 25, 'Economy class return airfare.'),
(42, 25, '4 Nights accommodations at Bosss Hotel or similar at Singapore.'),
(43, 25, 'Daily Breakfast at hotel.'),
(44, 25, 'Return Airport Transfer on Emt exclusive transfers.'),
(45, 25, 'Half Day City Tour Shared Basis'),
(46, 25, 'Night Safari on Shared Basis.'),
(47, 25, 'GST');

-- --------------------------------------------------------

--
-- Table structure for table `itinerary`
--

CREATE TABLE `itinerary` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `itinerary`
--

INSERT INTO `itinerary` (`id`, `package_id`, `day_number`, `title`, `description`) VALUES
(1, 1, 1, 'Kandy City Tour', 'In the morning, after the breakfast proceed for Kandy City Tour. Visit Market Crafts Centre , Upper Lake Drive, Gem Museum. Kandy the hill capital, venue of the annual Perahera the last stronghold of the Sinhala Kings was finally ceded to the British in 1815.'),
(10, 8, 1, 'Day 1 Srinagar Airport', 'Upon arrival at the Srinagar Airport & meet our representative and drive to Srinagar. Check into the hotel and also enjoy a 1 hr shikara ride in Dal Lake at Nehru Park on boulevard road. On return visit a local Handicrafts Showroom for hand-knotted specialty silken carpets, shawls, jewellery ornaments. Overnight stay in hotel'),
(17, 20, 1, 'Day 1 Arrive in Tokyo', 'Visit Fushimi Inari Shrine, Kinkakuji Temple (Golden Pavilion), and Arashiyama Bamboo Grove.'),
(18, 21, 1, 'Arrived Bagdogra', 'On arrival at NJP Railway Station / Bagdogra (IXB) Airport you will be received by ZiniGo Executive who will assist you to board your vehicle to Gangtok. Upon arrival proceed for check-in at hotel. Evening free for relaxation. Overnight stay at Gangtok.'),
(20, 22, 1, 'Delhi – Shimla', 'Pick-up from Delhi by our representative, drive to Shimla. It’s a Capital of Himachal Pradesh. Shimla is well known for its dense forests of pine and oak, adventure sports like Fishing, Skiing, Golfing and Trekking etc. This place is also renowned for its number of adventure sports activities which amplify the joy of visitors who come here for enjoyable holidays. On the way, you will pass through Kalka, Parwanoo, Barog and Solan on the way to Shimla. Check in at the Hotel. Overnight Stay at the Hotel in Shimla.'),
(21, 22, 2, ' Shimla – Kufri - Shimla', 'After morning Breakfast , visit Kufri which is situated at an altitude of 2500 Mtrs, Horse riding is one of the most popular activities tourists can enjoy here. While in Kufri horse riding is a must in order to explore the highland of Kufri. Starting from the base of Himalayan National Park, tourists can enjoy a visit to the apple orchards and Temples. Later in the evening explore shimla city, visiting Indian Institute of advanced studies, Lakkar Bazaar, Christ Church Known to be the second oldest church in northern India. Jhaku Temple which is dedicated to Lord Hanuman, Its Situated at the highest peak of Jakhu hill at an altitude of 2.5 km. Enjoy at mall road , It offers a variety of shopping, sport and entertainment activities. Overnight Stay at Shimla Hotel.'),
(22, 22, 3, 'Shimla – Manali ( 257 Kms | 7-8 Hrs )', 'After Breakfast, check out from the Hotel & drive to Manali, a Valley of Gods at the northern end of the Kullu Valley in Himachal Pradesh, is a hill station situated at a height of 2050 m (6398 ft) in the Himalayas. Also visit the Kullu on the way to Manali. Manali, is the one of the  perfect hill station of India where snow covered the huge mountains, Flowering orchards, interesting sightseeing and breathtaking adventure sports make any holiday seeker\'s dream come true and memorable too. Arrive at Manali and check in the hotel, rest of the day is at leisure to visit the local markets overnight stay at hotel at Manali.'),
(23, 22, 4, 'Manali – Solang Valley –Manali ( 51 Kms | 02 Hrs ', 'After Early morning Breakfast, proceed to Solang Valley ( Snow Point ) Situated at 13 km northwest of Manali, particularly a pictorial spot presenting awesome scenery of snow clad mountains and glaciers. The most common sports at Solang Valley are parachuting, paragliding, skating and zorbing. Dinner at Hotel, Overnight Stay.'),
(24, 22, 5, 'Manali – Manali Local Sightseeing', 'Morning Breakfast, take a local sightseeing of Manali, Visiting Hadimba temple, the 450 years old Hidimbadevi Temple, the oldest temple in Manali, Vashisht Temple situated on the banks of the majestic River Ravi, Manu temple and picturesque Naggar Valley. Evening you can enjoy the mall road, later go back to the hotel. Enjoy your dinner. Overnight stay at the hotel.'),
(25, 22, 6, 'After breakfast, proceed to Delhi on your journey back home', 'After breakfast, proceed to Delhi on your journey back home.'),
(26, 23, 1, ' Delhi To Kasol', 'After arrival, check-in to the hotel and take rest, evening is free for leisure. Have dinner and overnight at hotel/resort at Kasol.'),
(27, 23, 2, ' Kasol Local Sightseen', 'Have breakfast and get ready to explore Kasol. Walk by the Parvati River, Visit Manikaran Sahib, Take a long walk to Malana Try Israeli Food and Shopping. Rest overnight in hotel/resort at Kasol.'),
(28, 23, 3, ' Kasol To Delhi', 'Have breakfast and proceed back to homeward destination with wistful memories.'),
(29, 24, 1, ' Arrival & Dhow Cruise Marina', 'A friendly yet professional driver will greet you as you head to the airport’s arrival area. Settle into a spotless vehicle before you will be transferred to your Dubai accommodation. Following a stress-free check-in, be ready for an enchanting dhow cruise along Dubai Marina. This cruise lasts about 90 minutes and treats you to a delicious buffet dinner, spectacular views, and a live traditional Tanura performance.'),
(30, 24, 2, ' Dubai City Tour & Evening Desert Safari', 'Experience Dubai’s unrivaled contrasts in a day! A guided city tour will lead you to Dubai’s legendary, historical, and even lesser-known attractions. Stroll the early 20th century Al Bastakiya Quarters quaint alleys, stop at Jumeirah Beach, enjoy a drive through Sheikh Zayed Road, and capture some fantastic pictures against Atlantis, The Palm, Burj Al Arab, Jumeirah Mosque, etc.'),
(31, 24, 3, 'Day at leisure', 'Utilize the day at your pace for shopping and exploring the region’s leisure and dining scene. You can also count on our specialist team if you need inspiration to plan your day. '),
(32, 24, 4, 'Departure', 'After hotel check-out, you can indulge in last-minute shopping, revisit an attraction, or spend your time in malls or souks, depending on your flight schedule. When it’s time to depart, your friendly driver will pick you up at the hotel or designated spot and drop you off at the airport.'),
(33, 25, 1, ' Arrival in Singapore', 'Arrive in Singapore and meet your representative.\r\nEMT Exclusive transfer to your hotel.\r\nCheck-in and relax.\r\nOvernight stay in Singapore.'),
(34, 25, 2, ' Half-Day City Tour', 'Enjoy breakfast at the hotel.\r\nProceed for a Half-Day City Tour (Shared Basis):\r\nExplore Singapore’s iconic landmarks.\r\nCapture beautiful pictures of Marina Bay Sands, Merlion Park, and more.\r\nVisit Mount Faber for breathtaking views from Faber Peak.\r\nRest of the day free for leisure or optional activities.\r\nOvernight stay in Singapore.'),
(35, 25, 3, ' Night Safari Adventure', 'Breakfast at the hotel.\r\nDay free for leisure or optional activities.\r\nEvening: Night Safari (Shared Basis):\r\nWitness the thrilling Thumbuakar tribal performance with fire displays.\r\nHop on a tram ride through the rainforest, spotting nocturnal animals.\r\nReturn to the hotel and overnight stay in Singapore.'),
(36, 25, 4, 'Half-Day Sentosa Tour', 'Breakfast at the hotel.\r\nProceed for Sentosa Island Tour (Shared Basis):\r\nEnjoy a scenic Cable Car Ride (2-way) with panoramic views.\r\nExplore Sentosa’s attractions and relax on the island.\r\nEnd the evening with the spectacular Wings of Time show.'),
(37, 25, 5, ' Departure from Singapore', 'Breakfast at the hotel.\r\nCheck-out and EMT Exclusive transfer to the airport for departure.');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `is_customizable` tinyint(1) DEFAULT 0,
  `is_expert_choice` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `destination_id`, `duration`, `original_price`, `old_price`, `is_customizable`, `is_expert_choice`, `created_at`) VALUES
(1, 'Maldives Honeymoon', 1, 5, 70000.00, 85000.00, 0, 1, '2025-02-23 06:08:58'),
(8, 'Group Departure of Kashmir ', 2, 3, 89999.00, 99999.00, 0, 1, '2025-03-04 14:14:53'),
(20, 'Best of Japan Cherry Blossom', 8, 4, 89999.00, 99999.00, 0, 1, '2025-03-07 18:34:19'),
(21, 'Group Departure Gangtok for 3 nights', 13, 3, 32999.00, 40999.00, 1, 0, '2025-03-08 06:55:31'),
(22, 'Beautiful Himachal', 12, 6, 19999.00, 24999.00, 0, 1, '2025-03-28 17:24:53'),
(23, 'Beautiful Kasol', 12, 3, 14999.00, 17999.00, 0, 1, '2025-04-02 06:36:20'),
(24, 'Arabian Summer Retreat', 9, 4, 27999.00, 30999.00, 0, 1, '2025-04-07 06:31:32'),
(25, 'Explore Singapore Group Departure', 3, 5, 68999.00, 88999.00, 1, 1, '2025-04-21 13:27:00');

-- --------------------------------------------------------

--
-- Table structure for table `package_availability`
--

CREATE TABLE `package_availability` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `available_seats` int(11) NOT NULL,
  `travel_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_availability`
--

INSERT INTO `package_availability` (`id`, `package_id`, `available_seats`, `travel_date`) VALUES
(1, 22, 18, '2025-05-11'),
(2, 23, 0, '2025-04-24'),
(3, 23, 12, '2025-04-25'),
(4, 23, 1, '2025-05-24'),
(6, 23, 14, '2025-05-31'),
(7, 24, 22, '2025-04-26'),
(8, 24, 52, '2025-05-10'),
(9, 24, 21, '2025-05-21'),
(10, 24, 31, '2025-05-15'),
(11, 25, 0, '2025-11-19'),
(12, 25, 8, '2025-05-21'),
(13, 25, 21, '2025-05-27'),
(14, 25, 12, '2025-06-08');

-- --------------------------------------------------------

--
-- Table structure for table `package_images`
--

CREATE TABLE `package_images` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_images`
--

INSERT INTO `package_images` (`id`, `package_id`, `image_url`) VALUES
(1, 1, 'Maldiv2.jpg'),
(2, 1, 'manali.jpg'),
(13, 8, 'kashmir2.jpg'),
(21, 20, 'japan3.png'),
(23, 21, 'northeast.png'),
(24, 21, 'northeast1.png'),
(25, 21, 'northeast2.png'),
(26, 22, 'shimla1.jpg'),
(27, 22, 'shimla2.jpg'),
(28, 22, 'shimla4.jpg'),
(29, 23, 'kasol.jpg'),
(30, 23, 'kasol2.jpg'),
(31, 23, 'kasol3.jpg'),
(32, 23, 'kasol4.jpg'),
(33, 24, 'dubai.jpg'),
(34, 24, 'dubai2.jpg'),
(35, 24, 'dubai3.jpg'),
(36, 24, 'dubai4.jpg'),
(37, 25, 'singapore2.png'),
(38, 25, 'singapore3.png'),
(39, 25, 'singapore4.png');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `order_amount` decimal(10,2) NOT NULL,
  `reference_id` varchar(50) NOT NULL,
  `transaction_status` varchar(20) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `transaction_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--



-- --------------------------------------------------------

--
-- Table structure for table `terms_conditions`
--

CREATE TABLE `terms_conditions` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms_conditions`
--

INSERT INTO `terms_conditions` (`id`, `package_id`, `content`) VALUES
(1, 1, 'Carry a valid photo ID during check-in.'),
(2, 1, 'Check-in time: 2:00 PM | Check-out time: 11:00 AM.'),
(3, 8, 'Personal expenses and travel insurance not included.'),
(4, 1, 'Follow COVID-19 safety measures at all locations.'),
(5, 24, 'A valid passport with at least 6 months validity is mandatory.'),
(6, 24, 'Visa charges are included unless otherwise specified.'),
(7, 24, 'Hotel standard check in time is 3 PM and check out time will be 11 AM'),
(8, 24, 'Tour operator reserves the right to cancel the trip for safety concerns.'),
(9, 24, 'Full payment is required at the time of booking to confirm your reservation.'),
(10, 25, 'At the time of Booking: 100% of total package cost will be taken'),
(11, 25, 'Confirmation(Itinerary / Hotel/ package cost) is subject to availability at the time of booking'),
(12, 25, 'Prices are subject to fluctuation without prior notice.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(100) NOT NULL,
  `username` varchar(250) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(300) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `mobile` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `mobile`, `address`, `profile_image`) VALUES
(1, 'Demo User', 'demo1@example.com', '123456', CURRENT_TIMESTAMP, '9999999999', 'Demo Address', NULL),
(2, 'Test User', 'demo2@example.com', '123456', CURRENT_TIMESTAMP, '8888888888', 'Test Address', NULL);
--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exclusions`
--
ALTER TABLE `exclusions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inclusions`
--
ALTER TABLE `inclusions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `package_availability`
--
ALTER TABLE `package_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `package_images`
--
ALTER TABLE `package_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`order_id`);

--
-- Indexes for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `exclusions`
--
ALTER TABLE `exclusions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `inclusions`
--
ALTER TABLE `inclusions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `itinerary`
--
ALTER TABLE `itinerary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `package_availability`
--
ALTER TABLE `package_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `package_images`
--
ALTER TABLE `package_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exclusions`
--
ALTER TABLE `exclusions`
  ADD CONSTRAINT `exclusions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inclusions`
--
ALTER TABLE `inclusions`
  ADD CONSTRAINT `inclusions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD CONSTRAINT `itinerary_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_availability`
--
ALTER TABLE `package_availability`
  ADD CONSTRAINT `package_availability_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_images`
--
ALTER TABLE `package_images`
  ADD CONSTRAINT `package_images_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD CONSTRAINT `terms_conditions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE admin AUTO_INCREMENT = 2;
ALTER TABLE users AUTO_INCREMENT = 3;
ALTER TABLE bookings AUTO_INCREMENT = 3;
ALTER TABLE payments AUTO_INCREMENT = 1;
