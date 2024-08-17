<h1>Qurux</h1>
  <p>
    Welcome to Qurux! This application will provide a fun and good understanding of the quran for those who already understand how to read the arabic language so beginner quran readers essentially. Try giving the audio a go if you misheard some of the words of a verse. Its mostly tailored towards muslims who have a memorisation difficulty so i made a place for them to learn and memorise the holy quran!
  </p>

  <h2>Features</h2>
  <ul>
    <li>Quizzes with Easy and Advanced difficulties</li>
    <li>Quizzes from learning each chapter to learning each section also known as Juz</li>
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
    <li>User Authentication: Laravel Breeze</li>
  </ul>

  <h2>Installation</h2>
  <p>To run this project locally, follow these steps:</p>
  <ol>
    <li>Clone the repository:
      <pre><code>git clone https://github.com/idristungub/Qurux.git</code></pre>
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
      <li>
          You may have to run Apache server and mySQL using XAMPP Control Panel <a href='https://www.apachefriends.org/download.html'>XAMPP download</a>
      </li>
    <li>Run database migrations and seed users and achievements:
      <pre><code>php artisan migrate:fresh --seed</code></pre>
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

  <h2>Screenshots</h2>
  
![ss1](https://github.com/user-attachments/assets/d51dc2aa-e629-4c76-ac94-d416adf2a1e2)
  
![ss3](https://github.com/user-attachments/assets/e25d4c5b-e290-417b-be77-4b746b42de8f)

![ss2](https://github.com/user-attachments/assets/253a2215-b4fc-4aaf-b138-800359b80dd3)

![ss4](https://github.com/user-attachments/assets/2734a173-4d46-4dde-8a43-624134878f1d)

![ss5](https://github.com/user-attachments/assets/f2756e36-2bc8-4d8b-a267-409e0c860304)

![ss6](https://github.com/user-attachments/assets/8579596d-a7b1-4b5a-a51c-d9036fcbf881)

![ss7](https://github.com/user-attachments/assets/4627386e-d2b1-4ead-a013-bfdb3e3ab391)

![ss18](https://github.com/user-attachments/assets/28d02234-f5e3-4a8f-bf05-3295c52ac77b)

![ss8](https://github.com/user-attachments/assets/068e14c8-a704-4391-bdbd-1fcdf8d2b916)

![ss9](https://github.com/user-attachments/assets/2648eb5d-ef0f-480d-9d3b-a69b914e2b6f)

![ss10](https://github.com/user-attachments/assets/41c8f4ca-443c-4742-be7d-1a945b878e92)

![ss19](https://github.com/user-attachments/assets/e15f1504-2e05-43ad-b23b-bd1681b8b7ae)

![ss11](https://github.com/user-attachments/assets/5ea03574-a931-467d-93a1-8ef7cc70939a)

![ss12](https://github.com/user-attachments/assets/b3b21b0c-9bd3-4550-a030-584cbfaa3695)

![ss14](https://github.com/user-attachments/assets/6c45a05e-1b00-464f-a238-280e00bd570d)

![ss15](https://github.com/user-attachments/assets/bed4d411-021b-43db-a9dc-5a7eb284dade)

![ss16](https://github.com/user-attachments/assets/301bfded-3e13-4d8b-86c3-fab7d33a6a16)

![ss17](https://github.com/user-attachments/assets/3c487f4d-4eb0-4dff-b8ec-c2c247dcff6e)



  <h2>License</h2>
  <p>This project is licensed under the MIT License. See the <a href="LICENSE">LICENSE</a> file for details.</p>

  <h2>Contact</h2>
  <p>If you have any questions or suggestions or even any errors that come by, feel free to reach out to the project owner myself at <a href="mailto:idristungub@gmail.com">idristungub@gmail.com</a>.</p>
