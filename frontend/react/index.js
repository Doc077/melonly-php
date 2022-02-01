import React from 'react'
import { createRoot } from 'react-dom'

const MelonlyComponent = () => {
    return (
        <div className="content">
            Melonly with React.js 💙
        </div>
    )
}

const root = createRoot(document.querySelector('#root'))

root.render(<MelonlyComponent />)
