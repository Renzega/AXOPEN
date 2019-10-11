//
//  LCModel.swift
//  Light Community
//
//  Created by Samuel Clauzon on 09/06/2019.
//  Copyright © 2019 Samuel Clauzon. All rights reserved.
//

import Foundation

class LC {
    
    enum LoginStates {
        case badLogin
        case noAccountVerification
        case successLogin
    }
    
    static var loginState: LoginStates = .badLogin
    
    static func parseJSON(_ data: Data) {
        var jsonResult = [String]()
        
        do {
            jsonResult = try JSONSerialization.jsonObject(with: data, options:JSONSerialization.ReadingOptions.allowFragments) as! NSArray as! [String]
        } catch let error as NSError {
            loginState = .badLogin
            print(error)
        }
        
        DispatchQueue.main.async(execute: { () -> Void in
            
            switch jsonResult[0] {
            case "Success":
                loginState = .successLogin
            case "No account verification":
                loginState = .noAccountVerification
            case "Error":
                loginState = .badLogin
            default:
                loginState = .badLogin
            }
            
            let name = Notification.Name("LoginManagerNotification")
            let notification = Notification(name: name)
            NotificationCenter.default.post(notification)
        })
    }
    
    static func parseJSONAlreadyConnected(_ data: Data) {
        var jsonResult = [String]()
        
        do {
            jsonResult = try JSONSerialization.jsonObject(with: data, options:JSONSerialization.ReadingOptions.allowFragments) as! NSArray as! [String]
        } catch let error as NSError {
            loginState = .badLogin
            print(error)
        }
        
        DispatchQueue.main.async(execute: { () -> Void in
            
            switch jsonResult[0] {
            case "Success":
                loginState = .successLogin
            case "No account verification":
                loginState = .noAccountVerification
            case "Error":
                loginState = .badLogin
            default:
                loginState = .badLogin
            }
            
            let name = Notification.Name("LoginAlreadyConnectedManagerNotification")
            let notification = Notification(name: name)
            NotificationCenter.default.post(notification)
        })
    }
    
    static func parseJSONLoadData(_ data: Data) {
        var jsonResult = [String]()
        
        do {
            jsonResult = try JSONSerialization.jsonObject(with: data, options:JSONSerialization.ReadingOptions.allowFragments) as! [String]
        } catch let error as NSError {
            Student.isConnected = false
            print(error)
        }
        
        DispatchQueue.main.async(execute: { () -> Void in
            
            if (jsonResult[0] != "Error") {
                for currentResult in 0..<jsonResult.count {
                    Student.data.append(jsonResult[currentResult])
                }
            } else {
                Student.isConnected = false
            }
            
            let name = Notification.Name("studentDataLoadedNotification")
            let notification = Notification(name: name)
            NotificationCenter.default.post(notification)
            
        })
    }
    
}
