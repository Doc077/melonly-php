import React from 'react'
import ReactDOM from 'react-dom'

const MelonlyComponent = () => {
    return (
        <div className="content">
            Melonly with React.js 💙
        </div>
    )
}

ReactDOM.render(<MelonlyComponent />, document.querySelector('#root'))
