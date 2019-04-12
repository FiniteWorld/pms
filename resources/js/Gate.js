export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.type === "Admin";
    }

    isManager() {
        return this.user.type === "Manager";
    }

    isMember() {
        return this.user.type === "Member";
    }

    isAdminOrManager() {
        if (this.user.type === "Admin" || this.user.type === "Manager") {
            return true;
        }
    }
    isManagerOrMember() {
        if (this.user.type === "Manager" || this.user.type === "Member") {
            return true;
        }
    }
}
