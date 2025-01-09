import { useState, useEffect } from "react";

function App() {
  const [notes, setNotes] = useState([]);
  const [error, setError] = useState("");

  // Fetch notes from the API
  useEffect(() => {
    const fetchNotes = async () => {
      try {
        const response = await fetch("http://localhost/ujian_pweb/backend/note.php");
        if (!response.ok) {
          throw new Error("Failed to fetch notes");
        }
        const data = await response.json();
        setNotes(data); // Assuming the API returns an array of notes
      } catch (err) {
        setError(err.message);
      }
    };

    fetchNotes();
  }, []);

  return (
    <div>
      <nav className="bg-white border-gray-200 shadow-md">
        <div className="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
          <a href="/" className="flex items-center space-x-3 rtl:space-x-reverse">
            <img
              src="https://flowbite.com/docs/images/logo.svg"
              className="h-8"
              alt="Flowbite Logo"
            />
            <span className="self-center text-2xl font-semibold whitespace-nowrap">
              Flowbite
            </span>
          </a>
          <div className="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button
              type="button"
              className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center"
            >
              Keluar
            </button>
          </div>
        </div>
      </nav>

      <div className="container mx-auto mt-6">
        <div className="relative overflow-x-auto">
          <div className="flex items-center justify-between flex-column md:flex-row flex-wrap space-y-4 md:space-y-0 py-4 bg-white">
            <div>
              <button
                id="dropdownActionButton"
                className="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5"
                type="button"
              >
                Tambah Data
              </button>
            </div>
          </div>
          <table className="w-full text-sm text-left text-gray-500">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" className="px-6 py-3">
                  Title
                </th>
                <th scope="col" className="px-6 py-3">
                  Content
                </th>
                <th scope="col" className="px-6 py-3">
                  Action
                </th>
              </tr>
            </thead>
            <tbody>
              {error ? (
                <tr>
                  <td colSpan="3" className="px-6 py-4 text-red-500">
                    {error}
                  </td>
                </tr>
              ) : notes.length > 0 ? (
                notes.map((note) => (
                  <tr key={note.id} className="bg-white border-b hover:bg-gray-50">
                    <td className="px-6 py-4">{note.title}</td>
                    <td className="px-6 py-4">{note.content}</td>
                    <td className="flex gap-2 px-6 py-4">
                      <a
                        href="#"
                        type="button"
                        className="font-medium text-blue-600 hover:underline"
                      >
                        Edit
                      </a>
                      <a
                        href="#"
                        type="button"
                        className="font-medium text-red-600 hover:underline"
                      >
                        Delete
                      </a>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="3" className="px-6 py-4 text-center">
                    No notes available
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

export default App;
