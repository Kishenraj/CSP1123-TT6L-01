body {
  font-family: 'Segoe UI', sans-serif;
  background: #f4f7fa;
  color: #333;
  margin: 0;
  padding: 0;
}

.nav {
  display: flex;
  justify-content: flex-end;
  background-color: #1f2937;
  padding: 10px 20px;
}

.nav-link {
  color: white;
  text-decoration: none;
  margin-left: 20px;
  font-size: 16px;
  font-weight: bold;
  transition: 0.3s;
}

.nav-link:hover {
  color: #38bdf8;
}

.container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

h2 {
  text-align: center;
  color: #1f2937;
  margin-bottom: 30px;
}

.items {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.item-card {
  background: #ffffff;
  width: 280px;
  border: 1px solid #ddd;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  text-align: left;
}

.item-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.no-image {
  width: 100%;
  height: 200px;
  background: #e5e7eb;
  color: #6b7280;
  display: flex;
  align-items: center;
  justify-content: center;
  font-style: italic;
  font-size: 14px;
}

.info {
  padding: 15px;
  font-size: 14px;
  color: #374151;
}
