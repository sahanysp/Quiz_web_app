import { useMemo, useState } from 'react'
import { useNavigate } from 'react-router-dom'

const questions = [
  {
    id: 1,
    question: 'What does HTML stand for?',
    options: ['Hyper Text Markup Language', 'Hyperlinks and Text Markup Language', 'Home Tool Markup Language', 'Hyper Tool Markup Language'],
    answer: 'Hyper Text Markup Language',
  },
  {
    id: 2,
    question: 'Which property is used to change the background color in CSS?',
    options: ['color', 'bgcolor', 'background-color', 'background'],
    answer: 'background-color',
  },
  {
    id: 3,
    question: 'How do you write "Hello World" in an alert box?',
    options: ['msg("Hello World");', 'alert("Hello World");', 'msgBox("Hello World");', 'alertBox("Hello World");'],
    answer: 'alert("Hello World");',
  },
  {
    id: 4,
    question: 'What does PHP stand for?',
    options: ['Personal Hypertext Processor', 'Private Home Page', 'PHP: Hypertext Preprocessor', 'Public Hypertext Page'],
    answer: 'PHP: Hypertext Preprocessor',
  },
  {
    id: 5,
    question: 'Choose the correct HTML element for the largest heading:',
    options: ['<head>', '<h6>', '<heading>', '<h1>'],
    answer: '<h1>',
  },
  {
    id: 6,
    question: 'Which CSS property is used to change the text color of an element?',
    options: ['fgcolor', 'color', 'text-color', 'font-color'],
    answer: 'color',
  },
  {
    id: 7,
    question: 'How do you declare a JavaScript variable?',
    options: ['v carName;', 'variable carName;', 'var carName;', 'val carName;'],
    answer: 'var carName;',
  },
  {
    id: 8,
    question: 'All variables in PHP start with which symbol?',
    options: ['!', '$', '&', '@'],
    answer: '$',
  },
  {
    id: 9,
    question: 'What is the correct way to write a JavaScript array?',
    options: ['var colors = 1 = ("red"), 2 = ("green"), 3 = ("blue")', 'var colors = "red", "green", "blue"', 'var colors = ["red", "green", "blue"]', 'var colors = (1:"red", 2:"green", 3:"blue")'],
    answer: 'var colors = ["red", "green", "blue"]',
  },
  {
    id: 10,
    question: 'How do you get information from a form that is submitted with the "get" method in PHP?',
    options: ['$_GET', 'Request.Form', 'Request.QueryString', '$_POST'],
    answer: '$_GET',
  },
]

function Quiz() {
  const [currentIndex, setCurrentIndex] = useState(0)
  const [answers, setAnswers] = useState({})
  const navigate = useNavigate()

  const currentQuestion = questions[currentIndex]

  const progress = useMemo(
    () => Math.round(((currentIndex + 1) / questions.length) * 100),
    [currentIndex],
  )

  const handleOptionChange = (option) => {
    setAnswers((prev) => ({ ...prev, [currentQuestion.id]: option }))
  }

  const nextQuestion = () => {
    if (currentIndex < questions.length - 1) {
      setCurrentIndex((prev) => prev + 1)
    }
  }

  const previousQuestion = () => {
    if (currentIndex > 0) {
      setCurrentIndex((prev) => prev - 1)
    }
  }

  const submitQuiz = () => {
    const score = questions.reduce((total, question) => {
      return answers[question.id] === question.answer ? total + 1 : total
    }, 0)

    navigate('/results', {
      state: {
        score,
        total: questions.length,
      },
    })
  }

  return (
    <div className="quiz-page bg-light">
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-lg-8">

            {/* Quiz Header & Progress */}
            <div className="quiz-header">
              <h2 className="quiz-title">Tech Knowledge Quiz</h2>
              <div className="quiz-progress-text">
                Question {currentIndex + 1} of {questions.length}
              </div>
            </div>

            <div className="quiz-progress">
              <div className="progress-bar" style={{ width: `${progress}%` }}></div>
            </div>

            {/* Question Card */}
            <div className="question-card">
              <div className="question-number">Question {currentIndex + 1}</div>
              <h3 className="question-text">{currentQuestion.question}</h3>

              <div className="options-container">
                {currentQuestion.options.map((option) => {
                  const isSelected = answers[currentQuestion.id] === option;

                  return (
                    <label
                      key={option}
                      className={`option-label ${isSelected ? 'selected' : ''}`}
                    >
                      <input
                        type="radio"
                        className="quiz-radio"
                        name={`question-${currentQuestion.id}`}
                        value={option}
                        checked={isSelected}
                        onChange={() => handleOptionChange(option)}
                      />
                      <span className="option-letter"></span>
                      <span className="option-text">{option}</span>
                    </label>
                  );
                })}
              </div>

              {/* Navigation Buttons */}
              <div className="quiz-nav-buttons">
                <button
                  className="btn-quiz-nav btn-prev"
                  onClick={previousQuestion}
                  disabled={currentIndex === 0}
                >
                  Previous
                </button>

                {currentIndex === questions.length - 1 ? (
                  <button className="btn-quiz-nav btn-submit" onClick={submitQuiz} disabled={!answers[currentQuestion.id]}>
                    Submit Answer
                  </button>
                ) : (
                  <button className="btn-quiz-nav btn-next" onClick={nextQuestion} disabled={!answers[currentQuestion.id]}>
                    Next Question
                  </button>
                )}
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  )
}

export default Quiz
