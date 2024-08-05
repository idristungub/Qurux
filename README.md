<h1>Qurux</h1>
  <p>
    Welcome to Qurux! This application offers a fun and interactive way to test your knowledge of the Quran. With quizzes of varying difficulty levels, users can challenge themselves with easy and advanced questions. The project includes achievements and leaderboards, encouraging friendly competition and aiding in memorization of the Quran.
  </p>

  <h2>Features</h2>
  <ul>
    <li>Quizzes with Easy and Advanced difficulties</li>
    <li>Achievements for reaching specific milestones</li>
    <li>Leaderboards to compete with other users</li>
    <li>User authentication and profile management</li>
    <li>Responsive design for mobile and desktop</li>
  </ul>

  <h2>Technology Stack</h2>
  <ul>
    <li>Frontend: Vue.js with Vite</li>
    <li>Backend: Laravel</li>
    <li>Database: MySQL</li>
    <li>Authentication: Laravel Sanctum</li>
  </ul>

  <h2>Installation</h2>
  <p>To run this project locally, follow these steps:</p>
  <ol>
    <li>Clone the repository:
      <pre><code>git clone https://github.com/your-username/quran-quiz.git](https://github.com/idristungub/Qurux.git</code></pre>
    </li>
    <li>Navigate to the project directory:
      <pre><code>cd qurux</code></pre>
    </li>
    <li>Install frontend dependencies:
      <pre><code>cd frontend
npm install</code></pre>
    </li>
    <li>Install backend dependencies:
      <pre><code>cd backend
composer install</code></pre>
    </li>
    <li>Create a copy of the <code>.env.example</code> file in the backend directory and rename it to <code>.env</code>. Update the database and other configuration settings.</li>
    <li>Generate the application key:
      <pre><code>php artisan key:generate</code></pre>
    </li>
    <li>Run database migrations:
      <pre><code>php artisan migrate</code></pre>
    </li>
    <li>Start the development server:
      <ul>
        <li>Frontend: <pre><code>npm run dev</code></pre></li>
        <li>Backend: <pre><code>php artisan serve</code></pre></li>
      </ul>
    </li>
  </ol>

  <h2>Usage</h2>
  <p>Once the application is running, you can access it by navigating to <code>http://localhost:3000</code> for the frontend and <code>http://localhost:8000</code> for the backend. Create an account or log in, select a quiz, and start testing your knowledge!</p>



  <h2>License</h2>
  <p>This project is licensed under the MIT License. See the <a href="LICENSE">LICENSE</a> file for details.</p>

  <h2>Contact</h2>
  <p>If you have any questions or suggestions, feel free to reach out to the project maintainer at <a href="mailto:your-email@example.com">your-email@example.com</a>.</p>
