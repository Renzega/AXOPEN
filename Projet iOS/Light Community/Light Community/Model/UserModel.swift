//
//  LoginModel.swift
//  Light Community
//
//  Created by Samuel Clauzon on 09/06/2019.
//  Copyright © 2019 Samuel Clauzon. All rights reserved.
//

import Foundation
import CoreData

class User: NSManagedObject {
    
}

extension String {
    func isValidEmail() -> Bool {
        let regex = try! NSRegularExpression(pattern: "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$", options: .caseInsensitive)
        return regex.firstMatch(in: self, options: [], range: NSRange(location: 0, length: count)) != nil
    }
    func isValidText() -> Bool {
        let regex = try! NSRegularExpression(pattern: "([A-Z][a-zA-Z]*)", options: [])
        return regex.firstMatch(in: self, options: [], range: NSRange(location: 0, length: count)) != nil
    }
}
