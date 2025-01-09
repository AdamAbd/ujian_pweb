import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import Navbar from "./component/Navbar";

function EditNote() {
    const { id } = useParams(); // Get the note ID from the URL
    const navigate = useNavigate(); // For navigation after update

    const [formData, setFormData] = useState({ title: "", content: "" });
    const [message, setMessage] = useState("");
    const [error, setError] = useState("");

    // Fetch the existing note data
    useEffect(() => {
        const fetchNote = async () => {
            try {
                const response = await fetch(`http://localhost/ujian_pweb/backend/note.php?id=${id}`);
                if (!response.ok) {
                    throw new Error("Failed to fetch note");
                }
                const data = await response.json();

                setFormData({ title: data[0].title, content: data[0].content });
            } catch (err) {
                setError(err.message);
            }
        };

        fetchNote();
    }, [id]);

    // Handle input changes
    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    // Handle form submission
    const handleSubmit = async (e) => {
        e.preventDefault();
        setMessage("");
        setError("");

        try {
            const response = await fetch(`http://localhost/ujian_pweb/backend/note.php?id=${id}`, {
                method: "PUT", // Use PUT for updating the note
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            });

            if (!response.ok) {
                throw new Error("Failed to update note");
            }

            setMessage("Note updated successfully!");
            setTimeout(() => navigate("/"), 2000); // Redirect to the homepage after success
        } catch (err) {
            setError(err.message);
        }
    };

    return (
        <div>
            <Navbar />

            <form onSubmit={handleSubmit} className="container mx-auto mt-6">
                <h1 className="text-2xl font-bold mb-4">Edit Note</h1>
                <div className="mb-4">
                    <label htmlFor="title" className="block text-sm font-medium text-gray-700">
                        Title
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value={formData.title}
                        onChange={handleInputChange}
                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder={formData.title || "Loading..."}
                        required
                    />
                </div>
                <div className="mb-4">
                    <label htmlFor="content" className="block text-sm font-medium text-gray-700">
                        Content
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        value={formData.content}
                        onChange={handleInputChange}
                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder={formData.content || "Loading..."}
                        rows="4"
                        required
                    />
                </div>
                <button
                    type="submit"
                    className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2"
                >
                    Update Note
                </button>

                {/* Feedback Messages */}
                {message && <p className="text-green-500 mt-4">{message}</p>}
                {error && <p className="text-red-500 mt-4">{error}</p>}
            </form>
        </div>
    );
}

export default EditNote;
