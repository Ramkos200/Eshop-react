import React, { useState, useEffect } from "react";
import { useAuth } from "../context/AuthContext";
import { useNavigate } from "react-router-dom";
import { authService } from "../services/authService";

const Profile = () => {
    const { user, updateUser } = useAuth();
    const navigate = useNavigate();
    const [profile, setProfile] = useState({
        name: "",
        email: "",
        phone: "",
    });
    const [passwordForm, setPasswordForm] = useState({
        current_password: "",
        new_password: "",
        new_password_confirmation: "",
    });
    const [loading, setLoading] = useState(false);
    const [passwordLoading, setPasswordLoading] = useState(false);
    const [success, setSuccess] = useState("");
    const [error, setError] = useState("");
    const [passwordError, setPasswordError] = useState("");

    useEffect(() => {
        if (!user) {
            return;
        }

        // Initialize profile data from user context
        setProfile({
            name: user.name || "",
            email: user.email || "",
            phone: user.phone || "",
        });
    }, [user, navigate]);

    const handleProfileChange = (e) => {
        setProfile({
            ...profile,
            [e.target.name]: e.target.value,
        });
        setError("");
        setSuccess("");
    };

    const handlePasswordChange = (e) => {
        setPasswordForm({
            ...passwordForm,
            [e.target.name]: e.target.value,
        });
        setPasswordError("");
        setSuccess("");
    };

    const handleProfileSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError("");
        setSuccess("");

        try {
            // You'll need to add updateProfile method to authService
            const response = await authService.updateProfile(profile);
            updateUser(response.user);
            setSuccess("Profile updated successfully!");
        } catch (error) {
            setError(
                error.response?.data?.message || "Failed to update profile"
            );
        } finally {
            setLoading(false);
        }
    };

    const handlePasswordSubmit = async (e) => {
        e.preventDefault();
        setPasswordLoading(true);
        setPasswordError("");
        setSuccess("");

        // Validate passwords
        if (
            passwordForm.new_password !== passwordForm.new_password_confirmation
        ) {
            setPasswordError("New passwords do not match");
            setPasswordLoading(false);
            return;
        }

        if (passwordForm.new_password.length < 8) {
            setPasswordError("New password must be at least 8 characters");
            setPasswordLoading(false);
            return;
        }

        try {
            // You'll need to add changePassword method to authService
            await authService.changePassword(passwordForm);
            setSuccess("Password changed successfully!");
            setPasswordForm({
                current_password: "",
                new_password: "",
                new_password_confirmation: "",
            });
        } catch (error) {
            setPasswordError(
                error.response?.data?.message || "Failed to change password"
            );
        } finally {
            setPasswordLoading(false);
        }
    };

    if (!user) {
        return null;
    }

    return (
        <div className="max-w-4xl mx-auto px-4 py-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-8">
                My Profile
            </h1>

            {/* Success Message */}
            {success && (
                <div className="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded mb-6">
                    {success}
                </div>
            )}

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* Profile Information */}
                <div className="bg-white shadow-md rounded-lg p-6">
                    <h2 className="text-xl font-semibold text-gray-900 mb-6">
                        Profile Information
                    </h2>

                    <form onSubmit={handleProfileSubmit} className="space-y-6">
                        {error && (
                            <div className="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                                {error}
                            </div>
                        )}

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Full Name
                            </label>
                            <input
                                type="text"
                                name="name"
                                value={profile.name}
                                onChange={handleProfileChange}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input
                                type="email"
                                name="email"
                                value={profile.email}
                                onChange={handleProfileChange}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                disabled
                            />
                            <p className="text-sm text-gray-500 mt-1">
                                Email cannot be changed
                            </p>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input
                                type="tel"
                                name="phone"
                                value={profile.phone}
                                onChange={handleProfileChange}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter your phone number"
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? (
                                <div className="flex items-center justify-center">
                                    <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                    Updating...
                                </div>
                            ) : (
                                "Update Profile"
                            )}
                        </button>
                    </form>
                </div>

                {/* Change Password */}
                <div className="bg-white shadow-md rounded-lg p-6">
                    <h2 className="text-xl font-semibold text-gray-900 mb-6">
                        Change Password
                    </h2>

                    <form onSubmit={handlePasswordSubmit} className="space-y-6">
                        {passwordError && (
                            <div className="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                                {passwordError}
                            </div>
                        )}

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Current Password
                            </label>
                            <input
                                type="password"
                                name="current_password"
                                value={passwordForm.current_password}
                                onChange={handlePasswordChange}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter current password"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                New Password
                            </label>
                            <input
                                type="password"
                                name="new_password"
                                value={passwordForm.new_password}
                                onChange={handlePasswordChange}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter new password (min 8 characters)"
                                minLength="8"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Confirm New Password
                            </label>
                            <input
                                type="password"
                                name="new_password_confirmation"
                                value={passwordForm.new_password_confirmation}
                                onChange={handlePasswordChange}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Confirm new password"
                                minLength="8"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={passwordLoading}
                            className="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {passwordLoading ? (
                                <div className="flex items-center justify-center">
                                    <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                    Changing...
                                </div>
                            ) : (
                                "Change Password"
                            )}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default Profile;
