function Navbar() {
    return (
        <nav className="bg-white border-gray-200 shadow-md">
            <div className="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto py-4">
                <a href="/" className="text-2xl font-semibold whitespace-nowrap">
                    Notes+
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
    )
}

export default Navbar