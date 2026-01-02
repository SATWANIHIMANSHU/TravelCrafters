<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Modal</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .book-now-btn {
            background-color: #ff6f00;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }
        
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal {
            background-color: white;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        
        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 30px;
            height: 30px;
            background-color: #ff3b3b;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-title {
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }
        
        .modal-subtitle {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .date-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .date-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .room-section {
            position: relative;
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .room-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .room-title {
            font-weight: bold;
            font-size: 16px;
        }
        
        .delete-room {
            color: #999;
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .guest-selector {
            display: flex;
            justify-content: space-between;
        }
        
        .guest-type {
            width: 48%;
        }
        
        .guest-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .counter {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .counter button {
            width: 40px;
            height: 40px;
            background: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }
        
        .counter button:hover {
            background-color: #f5f5f5;
        }
        
        .counter-value {
            flex-grow: 1;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
        }
        
        .add-room {
            display: block;
            text-align: right;
            color: #2196F3;
            cursor: pointer;
            padding: 10px 0;
            font-weight: 500;
            text-decoration: none;
        }
        
        .calculate-btn {
            display: block;
            width: 100%;
            max-width: 320px;
            margin: 20px auto 10px;
            padding: 12px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .calculate-btn:hover {
            background-color: #0d8aee;
        }
        
        .separator {
            height: 1px;
            background-color: #eee;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Button to open the modal -->
    <button class="book-now-btn" id="bookNowBtn">Book Now</button>
    
    <!-- Modal Overlay -->
    <div class="modal-overlay" id="modalOverlay">
        <!-- Modal Container -->
        <div class="modal">
            <!-- Close Button -->
            <button class="modal-close" id="modalClose">✕</button>
            
            <!-- Header -->
            <div class="modal-header">
                <h2 class="modal-title">Group Departure Kerala 5 nights</h2>
                <p class="modal-subtitle">1N Cochin| 2N Munnar|1N Thekkady|1N Alleppey (Ex Delhi)</p>
                <div class="separator"></div>
            </div>
            
            <!-- Body -->
            <div class="modal-body">
                <!-- Date Section -->
                <div class="date-section">
                    <h3 class="section-title">Select Date</h3>
                    <input type="date" class="date-input" id="tripDate" placeholder="MM/DD/YYYY">
                </div>
                
                <!-- Rooms Section -->
                <div id="roomsContainer">
                    <div class="room-section" id="room1">
                        <div class="room-header">
                            <div class="room-title">Room 1</div>
                            <button class="delete-room" style="visibility: hidden;">🗑️</button>
                        </div>
                        
                        <div class="guest-selector">
                            <div class="guest-type">
                                <label class="guest-label">Adult</label>
                                <div class="counter">
                                    <button class="decrement" onclick="updateCounter('adult1', -1)">−</button>
                                    <div class="counter-value" id="adult1">2</div>
                                    <button class="increment" onclick="updateCounter('adult1', 1)">+</button>
                                </div>
                            </div>
                            
                            <div class="guest-type">
                                <label class="guest-label">Child</label>
                                <div class="counter">
                                    <button class="decrement" onclick="updateCounter('child1', -1)">−</button>
                                    <div class="counter-value" id="child1">0</div>
                                    <button class="increment" onclick="updateCounter('child1', 1)">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Add Room Link -->
                <a href="#" class="add-room" id="addRoom">+ Add Room</a>
                
                <!-- Calculate Button -->
                <button class="calculate-btn" id="calculateBtn">Calculate Amount</button>
            </div>
        </div>
    </div>
    
    <script>
        // DOM Elements
        const bookNowBtn = document.getElementById('bookNowBtn');
        const modalOverlay = document.getElementById('modalOverlay');
        const modalClose = document.getElementById('modalClose');
        const addRoomBtn = document.getElementById('addRoom');
        const roomsContainer = document.getElementById('roomsContainer');
        const calculateBtn = document.getElementById('calculateBtn');
        
        // Room counter
        let roomCount = 1;
        
        // Open Modal
        bookNowBtn.addEventListener('click', () => {
            modalOverlay.style.display = 'flex';
        });
        
        // Close Modal
        modalClose.addEventListener('click', () => {
            modalOverlay.style.display = 'none';
        });
        
        // Close when clicking outside the modal
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                modalOverlay.style.display = 'none';
            }
        });
        
        // Add Room
        addRoomBtn.addEventListener('click', (e) => {
            e.preventDefault();
            roomCount++;
            
            const newRoom = document.createElement('div');
            newRoom.className = 'room-section';
            newRoom.id = `room${roomCount}`;
            
            newRoom.innerHTML = `
                <div class="room-header">
                    <div class="room-title">Room ${roomCount}</div>
                    <button class="delete-room" onclick="deleteRoom(${roomCount})">🗑️</button>
                </div>
                
                <div class="guest-selector">
                    <div class="guest-type">
                        <label class="guest-label">Adult</label>
                        <div class="counter">
                            <button class="decrement" onclick="updateCounter('adult${roomCount}', -1)">−</button>
                            <div class="counter-value" id="adult${roomCount}">1</div>
                            <button class="increment" onclick="updateCounter('adult${roomCount}', 1)">+</button>
                        </div>
                    </div>
                    
                    <div class="guest-type">
                        <label class="guest-label">Child</label>
                        <div class="counter">
                            <button class="decrement" onclick="updateCounter('child${roomCount}', -1)">−</button>
                            <div class="counter-value" id="child${roomCount}">0</div>
                            <button class="increment" onclick="updateCounter('child${roomCount}', 1)">+</button>
                        </div>
                    </div>
                </div>
            `;
            
            roomsContainer.appendChild(newRoom);
        });
        
        // Update Counter (for adult and child counts)
        function updateCounter(id, change) {
            const counterEl = document.getElementById(id);
            let currentValue = parseInt(counterEl.textContent);
            
            // Apply the change with constraints
            if (id.startsWith('adult')) {
                // Adults: Minimum 1, Maximum 4
                currentValue = Math.max(1, Math.min(4, currentValue + change));
            } else {
                // Children: Minimum 0, Maximum 3
                currentValue = Math.max(0, Math.min(3, currentValue + change));
            }
            
            counterEl.textContent = currentValue;
        }
        
        // Delete Room
        function deleteRoom(roomId) {
            const roomToDelete = document.getElementById(`room${roomId}`);
            if (roomToDelete) {
                roomToDelete.remove();
            }
        }
        
        // Calculate Amount
        calculateBtn.addEventListener('click', () => {
            const tripDate = document.getElementById('tripDate').value;
            
            if (!tripDate) {
                alert("Please select a date");
                return;
            }
            
            // Collect data from all rooms
            const roomsData = [];
            
            for (let i = 1; i <= roomCount; i++) {
                const roomElement = document.getElementById(`room${i}`);
                
                if (roomElement) {
                    const adultCountEl = document.getElementById(`adult${i}`);
                    const childCountEl = document.getElementById(`child${i}`);
                    
                    if (adultCountEl && childCountEl) {
                        roomsData.push({
                            room: i,
                            adults: parseInt(adultCountEl.textContent),
                            children: parseInt(childCountEl.textContent)
                        });
                    }
                }
            }
            
            // Sample calculation logic (you can customize this)
            const basePrice = 3000; // Base price per adult
            const childPrice = 1500; // Price per child
            
            let totalAmount = 0;
            let totalAdults = 0;
            let totalChildren = 0;
            
            roomsData.forEach(room => {
                totalAdults += room.adults;
                totalChildren += room.children;
                totalAmount += (room.adults * basePrice) + (room.children * childPrice);
            });
            
            // Show the calculation result
            alert(`Booking Summary:
Date: ${tripDate}
Total Rooms: ${roomsData.length}
Total Adults: ${totalAdults}
Total Children: ${totalChildren}
Total Amount: ₹${totalAmount}`);
        });
    </script>
</body>
</html>