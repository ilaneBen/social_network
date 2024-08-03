import React from 'react';

const Logout = ({ onLogout }) => {
    // Vous pouvez ajouter des effets supplémentaires lors de la déconnexion si nécessaire
    const handleLogout = () => {
        // Implémentez ici la logique de déconnexion, par exemple, appel à une fonction de déconnexion
        // Cela peut inclure la suppression des tokens d'authentification, la mise à jour de l'état isLoggedIn, etc.
        onLogout();
    };

    // Optionnel : Afficher un message de confirmation de déconnexion ou effectuer d'autres actions nécessaires avant la déconnexion

    return (
        <div>
            <h2>Logout Page</h2>
            <button onClick={handleLogout}>Logout</button>
        </div>
    );
};

export default Logout;