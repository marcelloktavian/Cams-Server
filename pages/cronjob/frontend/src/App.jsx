import axios from 'axios'
import { useEffect, useState } from 'react'
import {API_HOST} from './config.json'

function App() {
  const [cron,setCron] = useState([])

  const getData = async () => {
    await axios.get(`${API_HOST}/api/cron`).then((d) => {
      setCron(d.data.data)
    })
  }


  useEffect(() => {

  document.title = "Cronjobs"

    const interval = setInterval(() => {
      getData()
    }, 5000);

    return () => {
      getData()
      clearInterval(interval)
    }
  },[])
  
  const startCron = async (name) => {
    await axios.post(`${API_HOST}/api/start/${name}`)
  }



  return (
    <>
      <div className='h-screen w-screen bg-gray-100 flex flex-col p-3'>
        <h1 className='text-xl mb-5 text-gray-800'>Background Process</h1>
        
        <table className="border-collapse w-full">
          <thead className='rounded-t-md'>
              <tr>
                  <th className='p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell'></th>
                  <th className="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Process</th>
                  <th className="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Waiting</th>
                  <th className="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Success</th>
                  <th className="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Fail</th>
                  <th className="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Total Process</th>
                  <th className="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell"></th>
              </tr>
          </thead>
          <tbody>
              {cron.map((c,i) => (
                <tr key={c} className="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
                    <td className="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                        <span className="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase"></span>
                        {i + 1}
                    </td>
                    <td className="w-full lg:w-auto p-3 text-gray-800 text-center border border-b  block lg:table-cell relative lg:static">
                        <span className="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Process</span>
                        {c.name}
                    </td>
                    <td className="w-full lg:w-auto p-3 text-gray-800 text-center border border-b  block lg:table-cell relative lg:static">
                        <span className="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Success</span>
                        {/* <span className="rounded bg-red-400 py-1 px-3 text-xs font-bold">deleted</span> */}
                        {c.pending}
                    </td>
                    <td className="w-full lg:w-auto p-3 text-gray-800 text-center border border-b  block lg:table-cell relative lg:static">
                        <span className="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Fail</span>
                        {c.done}
                    </td>
                    <td className="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                        <span className="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Fail</span>
                        {c.fail}
                    </td>
                    <td className="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                        <span className="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase"></span>
                        {c.total_process}
                    </td>
                    <td className="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                        <span className="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase"></span>
                        {c.pending > 0 && (<button className='px-4 w-32 py-2 bg-blue-500 text-white shadow-md rounded-md' onClick={() => startCron(c.name)}>Start</button>) }
                    </td>
                </tr>
              )) }
          </tbody>
      </table>
      </div>
    </>
  )
}

export default App
