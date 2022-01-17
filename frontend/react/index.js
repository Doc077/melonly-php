import React from 'react'
import ReactDOM from 'react-dom'

const MelonlyComponent = () => {
    return (
        <div className="content">
            Melonly with React.js 💙
        </div>
    )
}

const root = ReactDOM.createRoot(document.querySelector('#root'))

root.render(<MelonlyComponent />)
